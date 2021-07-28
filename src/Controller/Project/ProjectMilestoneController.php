<?php

namespace App\Controller\Project;

use App\Entity\ProjectMilestone;
use App\Entity\Log;
use App\Form\ProjectMilestoneType;
use App\Repository\ProjectRepository;
use App\Repository\ProjectMilestoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/project/{project}/milestones')]
class ProjectMilestoneController extends AbstractController
{
    #[Route('/', name: 'project_milestone_index', methods: ['GET'])]
    public function index(ProjectMilestoneRepository $projectMilestoneRepository, ProjectRepository $projectRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $this->denyAccessUnlessGranted('project_milestone_index');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $projectMilestones = $paginator->paginate($projectMilestoneRepository->findBy(['project'=>$request->attributes->get('project')]), $request->query->getInt('page', 1), 10);
        return $this->render('project/milestone/index.html.twig', [
            'project_milestones' => $projectMilestones,
            'project' => $project,
        ]);
    }

    #[Route('/new', name: 'project_milestone_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProjectRepository $projectRepository): Response
    {
        $this->denyAccessUnlessGranted('project_milestone_create');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $projectMilestone = new ProjectMilestone();
        $form = $this->createForm(ProjectMilestoneType::class, $projectMilestone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $projectMilestone->setCreatedAt(new \DateTime());
            $projectMilestone->setCreatedBy($this->getUser());
            $projectMilestone->setProject($project);
            $entityManager->persist($projectMilestone);
            $entityManager->flush();
            $this->addFlash("success","created project milestone successfully.");

            return $this->redirectToRoute('project_milestone_index', ["project" => $project->getId()]);
        }

        return $this->render('project/milestone/new.html.twig', [
            'project_milestone' => $projectMilestone,
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }

    #[Route('/{id}', name: 'project_milestone_show', methods: ['GET'])]
    public function show(ProjectMilestone $projectMilestone, ProjectRepository $projectRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('project_milestone_show');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        return $this->render('project/milestone/show.html.twig', [
            'project_milestone' => $projectMilestone,
            'project' => $project,
        ]);
    }

    #[Route('/{id}/edit', name: 'project_milestone_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProjectRepository $projectRepository, ProjectMilestone $projectMilestone): Response
    {
        $this->denyAccessUnlessGranted('project_milestone_edit');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $form = $this->createForm(ProjectMilestoneType::class, $projectMilestone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success","Updated project milestone successfully.");

            return $this->redirectToRoute('project_milestone_index', ["project" => $project->getId()]);
        }

        return $this->render('project/milestone/edit.html.twig', [
            'project_milestone' => $projectMilestone,
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }

    #[Route('/{id}', name: 'project_milestone_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectRepository $projectRepository, ProjectMilestone $projectMilestone): Response
    {
        $this->denyAccessUnlessGranted('project_milestone_delete');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        if ($this->isCsrfTokenValid('delete'.$projectMilestone->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($projectMilestone);
            $entityManager->flush();
        }

        $this->addFlash("success","Deleted project milestone successfully.");

        return $this->redirectToRoute('project_milestone_index', ["project" => $project->getId()]);
    }
}
