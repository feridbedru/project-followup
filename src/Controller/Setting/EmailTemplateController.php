<?php

namespace App\Controller\Setting;

use App\Entity\EmailTemplate;
use App\Entity\Log;
use App\Form\EmailTemplateType;
use App\Repository\EmailTemplateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/emailtemplate')]
class EmailTemplateController extends AbstractController
{
    #[Route('/', name: 'email_template_index', methods: ['GET'])]
    public function index(EmailTemplateRepository $emailTemplateRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $this->denyAccessUnlessGranted('email_template_index');
        $emailTemplates = $paginator->paginate($emailTemplateRepository->findAll(), $request->query->getInt('page', 1), 10);
        return $this->render('setting/email_template/index.html.twig', [
            'email_templates' => $emailTemplates,
        ]);
    }

    #[Route('/new', name: 'email_template_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('email_template_create');
        $emailTemplate = new EmailTemplate();
        $form = $this->createForm(EmailTemplateType::class, $emailTemplate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($emailTemplate);
            $entityManager->flush();

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(),$this->getUser(),$emailTemplate->getId(),"EmailTemplate","CREATE", $emailTemplate);
            $entityManager->persist($log);
            $entityManager->flush();

            $this->addFlash("success","created email_template successfully.");

            return $this->redirectToRoute('email_template_index');
        }

        return $this->render('setting/email_template/new.html.twig', [
            'email_template' => $emailTemplate,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'email_template_show', methods: ['GET'])]
    public function show(EmailTemplate $emailTemplate): Response
    {
        $this->denyAccessUnlessGranted('email_template_show');
        return $this->render('setting/email_template/show.html.twig', [
            'email_template' => $emailTemplate,
        ]);
    }

    #[Route('/{id}/edit', name: 'email_template_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EmailTemplate $emailTemplate): Response
    {
        $this->denyAccessUnlessGranted('email_template_edit');
        $form = $this->createForm(EmailTemplateType::class, $emailTemplate);
        $original = clone $emailTemplate;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(),$this->getUser(),$emailTemplate->getId(),"EmailTemplate","UPDATE", $original ,$emailTemplate);
            $entityManager->persist($log);
            $entityManager->flush();

            $this->addFlash("success","Updated email_template successfully.");

            return $this->redirectToRoute('email_template_index');
        }

        return $this->render('setting/email_template/edit.html.twig', [
            'email_template' => $emailTemplate,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'email_template_delete', methods: ['POST'])]
    public function delete(Request $request, EmailTemplate $emailTemplate): Response
    {
        $this->denyAccessUnlessGranted('email_template_delete');
        if ($this->isCsrfTokenValid('delete'.$emailTemplate->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($emailTemplate);
            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(),$this->getUser(),$emailTemplate->getId(),"EmailTemplate","DELETE", $emailTemplate);
            $entityManager->persist($log);
            $entityManager->flush();
        }

        $this->addFlash("success","Deleted email_template successfully.");

        return $this->redirectToRoute('email_template_index');
    }
}
