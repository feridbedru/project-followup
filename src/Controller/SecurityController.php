<?php

namespace App\Controller;

use App\Entity\Permission;
use App\Entity\ProjectMembers;
use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class SecurityController extends AbstractController
{
    #[Route('/', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManager, RequestStack $requestStack): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        if ($lastUsername) {
            $user = $this->getUser();
            $role = $user->getRoles()[0];
            if ($role === "ROLE_USER") {
                if (!$user->getLastLogin()) {
                    return new RedirectResponse($this->urlGenerator->generate('change_password'));
                } elseif ($user->getIsActive() == 0) {
                    throw new CustomUserMessageAuthenticationException('Your account is temporarily inactive. Contact the system administrator');
                    return new RedirectResponse('app_login');
                }
            }

            $this->getUser()->setLastLogin(new \DateTime());
            $entityManager->flush();
            $permissions = [];

            if ($user->getId() == 1) {
                $permission = $entityManager->getRepository(Permission::class)->findAll();
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

            $session = $requestStack->getSession();
            $session->set('PERMISSION', $permissions);

            $projectMembersRepository = $entityManager->getRepository(ProjectMembers::class);
            $projectRepository = $entityManager->getRepository(Project::class);
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

            $session->set('myprojects', $project_list);
            return new RedirectResponse('dashboard');
        }else{
            return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        }
        
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    #[Route('/gen', name: 'generate')]
    public function generate()
    {
        $entity_name = 'Sponsorship Type';
        $entity_small = preg_replace('/\s+/', '_', strtolower($entity_name));
        echo "INSERT INTO `permission`(`name`, `code`, `description`) VALUES ('" . $entity_name . " Create','" . $entity_small . "_create', 'Allows users to create " . $entity_name . "');";
        echo "INSERT INTO `permission`(`name`, `code`, `description`) VALUES ('" . $entity_name . " Edit','" . $entity_small . "_edit', 'Allows users to edit " . $entity_name . "');";
        echo "INSERT INTO `permission`(`name`, `code`, `description`) VALUES ('" . $entity_name . " Delete','" . $entity_small . "_delete', 'Allows users to delete " . $entity_name . "');";
        echo "INSERT INTO `permission`(`name`, `code`, `description`) VALUES ('" . $entity_name . " Show','" . $entity_small . "_show', 'Allows users to view " . $entity_name . "');";
        echo "INSERT INTO `permission`(`name`, `code`, `description`) VALUES ('" . $entity_name . " List','" . $entity_small . "_index', 'Allows users to list " . $entity_name . "');";
        // return $entity_spaceless;
    }

    // #[Route('/email')]
    // public function sendEmail(MailerInterface $mailer)
    // {
    //     $email = (new Email())
    //         ->from('project.followup@mint.gov.et')
    //         ->to('ferid.bedru@mint.gov.et')
    //         ->subject('Time for Symfony Mailer!')
    //         ->text('Sending emails is fun again!')
    //         ->html('<p>See Twig integration for better HTML integration!</p>');

    //     $mailer->send($email);

    //     dd($mailer);
    // }
}
