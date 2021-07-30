<?php

namespace App\Controller\Project;

use App\Entity\Project;
use App\Entity\Log;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Repository\ProjectResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/project')]
class ProjectController extends AbstractController
{
    #[Route('/', name: 'project_index', methods: ['GET'])]
    public function index(ProjectRepository $projectRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $this->denyAccessUnlessGranted('project_index');
        $projects = $paginator->paginate($projectRepository->findAll(), $request->query->getInt('page', 1), 10);
        return $this->render('project/index.html.twig', [
            'projects' => $projects,
        ]);
    }
    
    #[Route('/{id}/dashboard', name: 'project_dashboard', methods: ['GET'])]
    public function dashboard(Project $project): Response
    {
        $this->denyAccessUnlessGranted('project_show');
        return $this->render('project/dashboard.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/new', name: 'project_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('project_create');
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $project->setCreatedBy($this->getUser());
            $project->setCreatedAt(new \DateTime());
            $entityManager->persist($project);
            $entityManager->flush();
            $this->addFlash("success","created project successfully.");

            return $this->redirectToRoute('project_show', ["id" => $project->getId()]);
        }

        return $this->render('project/new.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'project_show', methods: ['GET'])]
    public function show(Project $project, ProjectResourceRepository $projectResourceRepository): Response
    {
        $this->denyAccessUnlessGranted('project_show');
        $projectResource = $projectResourceRepository->findBy(['project'=>$project]);
        return $this->render('project/show.html.twig', [
            'project' => $project,
            'resources' => $projectResource,
        ]);
    }

    #[Route('/{id}/edit', name: 'project_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Project $project): Response
    {
        $this->denyAccessUnlessGranted('project_edit');
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success","Updated project successfully.");

            return $this->redirectToRoute('project_index');
        }

        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'project_delete', methods: ['POST'])]
    public function delete(Request $request, Project $project): Response
    {
        $this->denyAccessUnlessGranted('project_delete');
        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($project);
            $entityManager->flush();
        }

        $this->addFlash("success","Deleted project successfully.");

        return $this->redirectToRoute('project_index');
    }
}
