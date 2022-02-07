<?php

namespace App\Security;

use App\Entity\ProjectMembers;
use App\Entity\Project;
use App\Entity\User;
use App\Entity\Permission;
use App\Entity\UserGroup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class UserAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;
    private $user;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        return 'app_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Email could not be found.');
        }
        $this->user = $user;
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function getPassword($credentials): ?string
    {
        return $credentials['password'];
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $user = $this->user;
        $role = $user->getRoles()[0];
        if ($role === "ROLE_USER") {
            if (!$user->getLastLogin()) {
                return new RedirectResponse($this->urlGenerator->generate('change_password'));
            } elseif ($user->getIsActive() == false) {
                throw new CustomUserMessageAuthenticationException('Your account is temporarily inactive. Contact the system administrator');
                return new RedirectResponse('app_login');
            }
        }

        $this->user->setLastLogin(new \DateTime());
        $this->entityManager->flush();
        $permissions = [];

        if ($user->getId() == 1) {
            $permission = $this->entityManager->getRepository(Permission::class)->findAll();
            foreach ($permission as $key => $value1) {
                $permissions[] = $value1->getCode();
            }
        } else {
            $groups = $this->user->getUserGroup();
            foreach ($groups as $key => $value) {
                if (!$value->getIsActive()) {
                    continue;
                }
                $permission = $value->getPermission();

                foreach ($permission as $key => $value1) {
                    $permissions[] = $value1->getCode();
                }
            }
        }

        $request->getSession()->set(
            "PERMISSION",
            $permissions
        );

        $projectMembersRepository = $this->entityManager->getRepository(ProjectMembers::class);
        $projectRepository = $this->entityManager->getRepository(Project::class);
        $projectMember = $projectMembersRepository->findBy(['user' => $user, 'status' => 1]);
        $projects = [];
        $project_list = [];
        foreach ($projectMember as $member) {
            $project = $projectRepository->findOneBy(['id' => $member->getProject()->getId()]);
            array_push($projects, $project);
        }
        $managingProjects = $projectRepository->findBy(['project_manager' => $user]);
        foreach ($managingProjects as $projectr) {
            array_push($projects, $projectr);
        }
        foreach ($projects as $proj) {
            if (!in_array($proj, $project_list)) {
                $project_list[] = $proj;
            }
        }

        $request->getSession()->set(
            "myprojects",
            $project_list
        );

        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('dashboard'));

        throw new \Exception('TODO: provide a valid redirect inside ' . __FILE__);
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
