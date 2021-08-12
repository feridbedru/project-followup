<?php

namespace App\Controller\Project;

use App\Entity\Log;
use App\Form\ProjectEmailType;
use App\Repository\ProjectRepository;
use App\Repository\ProjectStructureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class ProjectEmailController extends AbstractController
{
    #[Route('/project/{project}/email', name: 'project_email_index')]
    public function index(ProjectRepository $projectRepository, PaginatorInterface $paginator, Request $request): Response
    {

        // $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);

        // $projectEmail = new ProjectEmail();
        // $form = $this->createForm(ProjectEmailType::class, $projectEmail);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $entityManager = $this->getDoctrine()->getManager();
        //     $projectEmail->setCreatedAt(new \DateTime());
        //     $projectEmail->setCreatedBy($this->getUser());
        //     $entityManager->persist($projectEmail);
        //     $entityManager->flush();
        //     $this->addFlash("success","Registered project email successfully.");

        //     return $this->redirectToRoute('project_email_index', ["project" => $project->getId()]);
        // }

        return $this->render('project/email/index.html.twig', [
            'controller_name' => 'ProjectEmailController',
        ]);
    }
}
