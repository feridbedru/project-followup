<?php

namespace App\Controller\Project;

use App\Entity\ProjectActivity;
use App\Entity\Project;
use App\Entity\ProjectMilestone;
use App\Entity\Log;
use App\Form\ProjectActivityType;
use App\Repository\ProjectRepository;
use App\Repository\ProjectActivityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/project/{project}/activity')]
class ProjectActivityController extends AbstractController
{
    #[Route('/', name: 'project_activity_index', methods: ['GET'])]
    public function index(ProjectActivityRepository $projectActivityRepository, ProjectRepository $projectRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $this->denyAccessUnlessGranted('project_activity_index');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $projectActivities = $paginator->paginate($projectActivityRepository->findBy(['project' => $project]), $request->query->getInt('page', 1), 10);
        return $this->render('project/activity/index.html.twig', [
            'project_activities' => $projectActivities,
            'project' => $project
        ]);
    }

    #[Route('/new', name: 'project_activity_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProjectRepository $projectRepository): Response
    {
        $this->denyAccessUnlessGranted('project_activity_create');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $projectActivity = new ProjectActivity(); 
        $form = $this->createForm(ProjectActivityType::class, $projectActivity, array('project' => $project->getId()));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $projectActivity->setCreatedAt(new \DateTime());
            $projectActivity->setCreatedBy($this->getUser());
            $projectActivity->setStatus(1);
            $projectActivity->setIsActive(1);
            $projectActivity->setProject($project);
            $entityManager->persist($projectActivity);
            $entityManager->flush();
            $this->addFlash("success","created project activity successfully.");

            return $this->redirectToRoute('project_activity_index', ["project" => $project->getId()]);
        }

        return $this->render('project/activity/new.html.twig', [
            'project_activity' => $projectActivity,
            'form' => $form->createView(),
            'project' => $project
        ]);
    }

    #[Route('/{id}', name: 'project_activity_show', methods: ['GET'])]
    public function show(ProjectActivity $projectActivity, ProjectRepository $projectRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('project_activity_show');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        return $this->render('project/activity/show.html.twig', [
            'project_activity' => $projectActivity,
            'project' => $project
        ]);
    }

    #[Route('/{id}/edit', name: 'project_activity_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProjectActivity $projectActivity, ProjectRepository $projectRepository): Response
    {
        $this->denyAccessUnlessGranted('project_activity_edit');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $form = $this->createForm(ProjectActivityType::class, $projectActivity, array('project' => $project->getId()));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success","Updated project activity successfully.");

            return $this->redirectToRoute('project_activity_index', ["project" => $project->getId()]);
        }

        return $this->render('project/activity/edit.html.twig', [
            'project_activity' => $projectActivity,
            'form' => $form->createView(),
            'project' => $project
        ]);
    }

    #[Route('/{id}', name: 'project_activity_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectActivity $projectActivity, ProjectRepository $projectRepository): Response
    {
        $this->denyAccessUnlessGranted('project_activity_delete');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        if ($this->isCsrfTokenValid('delete'.$projectActivity->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($projectActivity);
            $entityManager->flush();
        }

        $this->addFlash("success","Deleted project activity successfully.");

        return $this->redirectToRoute('project_activity_index', ["project" => $project->getId()]);
    }

    #[Route('/{id}/weight', name: 'project_activity_data', methods: ['POST'])]
    public function weight(Request $request, ProjectActivityRepository $projectActivityRepository): Response
    {
        $routeParams = $request->attributes->get('_route_params');
        $id = $routeParams['id'];
        $activities = $projectActivityRepository->findBy(['milestone' => $id]);
        $sum = array();
        foreach ($activities as $activity) {
           $weight = $activity->getWeight();
           array_push($sum, $weight);
        }

        $sum = array_sum($sum);
        $total = 100 - $sum;
        return new Response(json_encode($total));
    }

    #[Route('/order/{milestone}', name: 'project_activity_order', methods: ['POST','GET'])]
    public function order(Request $request, ProjectActivityRepository $projectActivityRepository): Response
    {
        $routeParams = $request->attributes->get('_route_params');
        $project = $routeParams['project'];
        $projectMilestone = $routeParams['milestone'];
        $max = $projectActivityRepository->findMaxOrder($project, $projectMilestone);
        return new Response(json_encode($max));
    }
}
