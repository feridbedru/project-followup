<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Entity\Log;
use App\Form\UserType;
use App\Repository\EmailTemplateRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Mailer\MailerInterface;
use App\Services\MailerService;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // $this->denyAccessUnlessGranted('user_index');
        $users = $paginator->paginate($userRepository->findAll(), $request->query->getInt('page', 1), 10);
        return $this->render('user_management/users/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/new', name: 'user_new', methods: ['GET', 'POST'])]
    public function new(Request $request,UserPasswordEncoderInterface $userPasswordEncoderInterface, MailerInterface $mailer, MailerService $mservice, EmailTemplateRepository $emailTemplateRepository): Response
    {
        $this->denyAccessUnlessGranted('user_create');
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setCreatedBy($this->getUser());
            $user->setCreatedAt(new \DateTime());
            $user->setIsActive(true);
            $user->setStatus(1);
            $user->setRoles(['ROLE_USER']);
            $user->setUsername($request->request->get('user')["email"]);
            $password = $this->randomPassword();
            $user->setPassword($userPasswordEncoderInterface->encodePassword($user,$password));
            $entityManager->persist($user);
            $entityManager->flush();

            $template = $emailTemplateRepository->findOneBy(['code' => 'user_account_created']);
            $message =  $template->getContent();
            $message = str_replace('$user', $user->getFullName(), $message);
            $message = str_replace('$email', $user->getEmail(), $message);
            $message = str_replace('$password', $password, $message);
            $recievers = array();
            array_push($recievers, $user->getEmail());
            $mservice->sendEmail($mailer, $recievers, $template->getName(), $message);

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(),$this->getUser(),$user->getId(),"User","CREATE", $user);
            $entityManager->persist($log);
            $entityManager->flush();
            $this->addFlash("success","created user successfully.");

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user_management/users/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    private function randomPassword()
    {
        $alphabet = 'AaBbCcDdEeFfGgHhIiJjKkLlMmNnPpQqRrSsTtUuVvWwXxYyZz1234567890!@#$%^&*().?';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        $password = implode($pass);
        return $password; 
    }

    #[Route('/{id}', name: 'user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        $this->denyAccessUnlessGranted('user_show');
        return $this->render('user_management/users/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user): Response
    {
        $this->denyAccessUnlessGranted('user_edit');
        $form = $this->createForm(UserType::class, $user);
        $original = clone $user;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(),$this->getUser(),$user->getId(),"User","UPDATE",$original, $user);
            $entityManager->persist($log);
            $entityManager->flush();

            $this->addFlash("success","Updated user successfully.");

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user_management/users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user): Response
    {
        $this->denyAccessUnlessGranted('user_delete');
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(),$this->getUser(),$user->getId(),"User","DELETE", $user);
            $entityManager->persist($log);
            $entityManager->flush();
        }

        $this->addFlash("success","Deleted user successfully.");

        return $this->redirectToRoute('user_index');
    }
}
