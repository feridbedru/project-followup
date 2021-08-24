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
    #[Route('/', name: 'project_milestone_index', methods: ['GET', 'POST'])]
    public function index(ProjectMilestoneRepository $projectMilestoneRepository, ProjectRepository $projectRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        // dd($project);
        if ($request->request->get('edit')) {

            $id = $request->request->get('edit');
            $projectMilestone = $projectMilestoneRepository->findOneBy(['id' => $id]);
            $form = $this->createForm(ProjectMilestoneType::class, $projectMilestone);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash("success", "Updated project milestone successfully.");

                return $this->redirectToRoute('project_milestone_index', ["project" => $project->getId()]);
            }

            $queryBuilder = $projectMilestoneRepository->findBy(['project' => $request->attributes->get('project')]);
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 10);

            return $this->render('project/milestone/index.html.twig', [
                'project_milestones' => $data,
                'form' => $form->createView(),
                'edit' => $id,
                'project' => $project,
            ]);
        }


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
            $this->addFlash("success", "Registered project milestone successfully.");

            return $this->redirectToRoute('project_milestone_index', ["project" => $project->getId()]);
        }

        $queryBuilder = $projectMilestoneRepository->findBy(['project' => $request->attributes->get('project')]);
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 10);
        return $this->render('project/milestone/index.html.twig', [
            'project_milestones' => $data,
            'form' => $form->createView(),
            'edit' => false,
            'project' => $project
        ]);
    }

    #[Route('/{id}', name: 'project_milestone_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectRepository $projectRepository, ProjectMilestone $projectMilestone): Response
    {
        $this->denyAccessUnlessGranted('project_milestone_delete');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        if ($this->isCsrfTokenValid('delete' . $projectMilestone->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($projectMilestone);
            $entityManager->flush();
        }
        $this->addFlash("success", "Deleted project_milestone successfully.");

        return $this->redirectToRoute('project_milestone_index', ["project" => $project->getId()]);
    }

    #[Route('/{id}/dates', name: 'project_milestone_dates', methods: ['POST'])]
    public function dates(ProjectMilestoneRepository $projectMilestoneRepository, Request $request): Response
    {
        $routeParams = $request->attributes->get('_route_params');
        $id = $routeParams['id'];
        $milestone = $projectMilestoneRepository->find($id);
        $dates = array();
        $start_date = $milestone->getStartDate();
        $start_date = date_format($start_date, "Y-m-d");
        array_push($dates, $start_date);
        $end_date = $milestone->getEndDate();
        $end_date = date_format($end_date, "Y-m-d");
        array_push($dates, $end_date);

        return new Response(json_encode($dates));
    }
}
