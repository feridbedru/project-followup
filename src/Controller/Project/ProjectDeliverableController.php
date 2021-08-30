<?php

namespace App\Controller\Project;

use App\Entity\ProjectDeliverable;
use App\Entity\Log;
use App\Form\ProjectDeliverableType;
use App\Repository\ProjectRepository;
use App\Repository\ProjectDeliverableRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/project/{project}/deliverable')]
class ProjectDeliverableController extends AbstractController
{
    #[Route('/', name: 'project_deliverable_index', methods: ['GET', 'POST'])]
    public function index(ProjectDeliverableRepository $projectDeliverableRepository, ProjectRepository $projectRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $deliverables = $projectDeliverableRepository->findBy(['project' => $project]);
        $sum = array();
        foreach ($deliverables as $deliverable) {
           $weight = $deliverable->getPercentage();
           array_push($sum, $weight);
        }

        $sum = array_sum($sum);
        $total = 100 - $sum;
        if ($request->request->get('edit')) {

            $id = $request->request->get('edit');
            $projectDeliverable = $projectDeliverableRepository->findOneBy(['id' => $id]);
            $original = clone $projectDeliverable;
            $form = $this->createForm(ProjectDeliverableType::class, $projectDeliverable, array('project' => $project->getId()));
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $log = new Log();
                $log =  $log->logEvent($request->getClientIp(), $this->getUser(), $projectDeliverable->getId(), "ProjectDeiverable", "UPDATE", $original, $projectDeliverable);
                $entityManager->persist($log);
                $entityManager->flush();
                $this->addFlash("success", "Updated project deliverable successfully.");

                return $this->redirectToRoute('project_deliverable_index', ["project" => $project->getId()]);
            }

            $queryBuilder = $projectDeliverableRepository->findBy(['project' => $project]);
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 10);

            return $this->render('project/deliverable/index.html.twig', [
                'project_deliverables' => $data,
                'form' => $form->createView(),
                'edit' => $id,
                'project' => $project,
                'max' => $total
            ]);
        }


        $projectDeliverable = new ProjectDeliverable();
        $form = $this->createForm(ProjectDeliverableType::class, $projectDeliverable, array('project' => $project->getId()));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $projectDeliverable->setCreatedBy($this->getUser());
            $projectDeliverable->setCreatedAt(new \DateTime());
            $projectDeliverable->setProject($project);
            $projectDeliverable->setVerifyDeliverable(0);
            $entityManager->persist($projectDeliverable);
            $entityManager->flush();

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(), $this->getUser(), $projectDeliverable->getId(), "ProjectDeliverable", "CREATE", $projectDeliverable);
            $entityManager->persist($log);
            $entityManager->flush();

            $this->addFlash("success", "Registered project deliverable successfully.");

            return $this->redirectToRoute('project_deliverable_index', ["project" => $project->getId()]);
        }

        $queryBuilder = $projectDeliverableRepository->findBy(['project' => $project]);
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 10);

        return $this->render('project/deliverable/index.html.twig', [
            'project_deliverables' => $data,
            'form' => $form->createView(),
            'edit' => false,
            'project' => $project,
            'max' => $total
        ]);
    }

    #[Route('/{id}', name: 'project_deliverable_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectDeliverable $projectDeliverable, ProjectRepository $projectRepository): Response
    {
        $this->denyAccessUnlessGranted('project_deliverable_delete');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        if ($this->isCsrfTokenValid('delete' . $projectDeliverable->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($projectDeliverable);
            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(), $this->getUser(), $projectDeliverable->getId(), "ProjectDeliverable", "DELETE", $projectDeliverable);
            $entityManager->persist($log);
            $entityManager->flush();
        }
        $this->addFlash("success", "Deleted project deliverable successfully.");

        return $this->redirectToRoute('project_deliverable_index', ["project" => $project->getId()]);
    }
}
