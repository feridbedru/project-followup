<?php

namespace App\Controller\Project;

use App\Entity\ActivityVerification;
use App\Entity\Log;
use App\Form\ActivityVerificationType;
use App\Repository\ActivityVerificationRepository;
use App\Repository\ProjectRepository;
use App\Repository\ProjectActivityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Services\ProjectAccessService;

#[Route('/project/{project}/activity/{activity}/verify')]
class ActivityVerificationController extends AbstractController
{
    #[Route('/', name: 'activity_verification_index', methods: ['GET', 'POST'])]
    public function index(ActivityVerificationRepository $activityVerificationRepository, ProjectRepository $projectRepository, ProjectActivityRepository $projectActivityRepository, PaginatorInterface $paginator, Request $request, ProjectAccessService $projectAccessService): Response
    {
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $projectAccessService->canUserAccessProject($this->getUser(), $project);
        $activity = $projectActivityRepository->findOneBy(['id' => $request->attributes->get('activity')]);
        if($request->request->get('edit')){
            
            $id = $request->request->get('edit');
            $activityVerification = $activityVerificationRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(ActivityVerificationType::class, $activityVerification);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash("success","Updated activity verification successfully.");

                return $this->redirectToRoute('activity_verification_index', ["project" => $project->getId(), "activity" => $activity->getId()]);
            }

            $queryBuilder = $activityVerificationRepository->findActivityVerification($request->query->get('search'));
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

            return $this->render('project/activity_verification/index.html.twig', [
                'activity_verifications' => $data,
                'form' => $form->createView(),
                'edit' => $id,
                'project' => $project,
                'activity' => $activity,
            ]);
        }

        
        $activityVerification = new ActivityVerification();
        $form = $this->createForm(ActivityVerificationType::class, $activityVerification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($activityVerification);
            $entityManager->flush();
            $this->addFlash("success","Registered activity verification successfully.");

            return $this->redirectToRoute('activity_verification_index', ["project" => $project->getId(), "activity" => $activity->getId()]);
        }

        $queryBuilder = $activityVerificationRepository->findActivityVerification($request->query->get('search'));
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

        return $this->render('project/activity_verification/index.html.twig', [
            'activity_verifications' => $data,
            'form' => $form->createView(),
            'edit' => false,
            'project' => $project,
            'activity' => $activity,
        ]);

    }

    #[Route('/{id}', name: 'activity_verification_delete', methods: ['POST'])]
    public function delete(Request $request, ActivityVerification $activityVerification, ProjectAccessService $projectAccessService, ProjectRepository $projectRepository, ProjectActivityRepository $projectActivityRepository): Response
    {
        $this->denyAccessUnlessGranted('activity_verification_delete');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $projectAccessService->canUserAccessProject($this->getUser(), $project);
        $activity = $projectActivityRepository->findOneBy(['id' => $request->attributes->get('activity')]);
        if ($this->isCsrfTokenValid('delete'.$activityVerification->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($activityVerification);
            $entityManager->flush();
        }
        $this->addFlash("success","Deleted activity verification successfully.");

        return $this->redirectToRoute('activity_verification_index', ["project" => $project->getId(), "activity" => $activity->getId()]);
    }
}
