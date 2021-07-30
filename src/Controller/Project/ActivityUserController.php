<?php

namespace App\Controller\Project;

use App\Entity\ActivityUser;
use App\Entity\Log;
use App\Form\ActivityUserType;
use App\Repository\ActivityUserRepository;
use App\Repository\ProjectRepository;
use App\Repository\ProjectActivityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('project/{project}/activity/{activity}/members')]
class ActivityUserController extends AbstractController
{
    #[Route('/', name: 'activity_user_index', methods: ['GET', 'POST'])]
    public function index(ActivityUserRepository $activityUserRepository, ProjectActivityRepository $projectActivityRepository, ProjectRepository $projectRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $activity = $projectActivityRepository->findOneBy(['id' => $request->attributes->get('activity')]);
        if($request->request->get('edit')){
            
            $id = $request->request->get('edit');
            $activityUser = $activityUserRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(ActivityUserType::class, $activityUser);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash("success","Updated activity user successfully.");

                return $this->redirectToRoute('activity_user_index', ["project" => $project->getId(), "activity" => $activity->getId()]);
            }

            $queryBuilder = $activityUserRepository->findBy(["activity" => $activity]);
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

            return $this->render('project/activity_user/index.html.twig', [
                'activity_users' => $data,
                'form' => $form->createView(),
                'edit' => $id,
                'project' => $project,
                'activity' => $activity,
            ]);
        }

        
        $activityUser = new ActivityUser();
        $form = $this->createForm(ActivityUserType::class, $activityUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $activityUser->setActivity($activity);
            $activityUser->setAssignedAt(new \DateTime());
            $activityUser->setAssignedBy($this->getUser());
            $activityUser->setStatus(1);
            $entityManager->persist($activityUser);
            $entityManager->flush();
            $this->addFlash("success","Registered activity user successfully.");

            return $this->redirectToRoute('activity_user_index', ["project" => $project->getId(), "activity" => $activity->getId()]);
        }

        $queryBuilder = $activityUserRepository->findBy(["activity" => $activity]);
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

        return $this->render('project/activity_user/index.html.twig', [
            'activity_users' => $data,
            'form' => $form->createView(),
            'edit' => false,
            'project' => $project,
            'activity' => $activity,
        ]);

    }

    #[Route('/{id}', name: 'activity_user_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectActivityRepository $projectActivityRepository, ProjectRepository $projectRepository, ActivityUser $activityUser): Response
    {
        $this->denyAccessUnlessGranted('activity_user_delete');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $activity = $projectActivityRepository->findOneBy(['id' => $request->attributes->get('activity')]);
        if ($this->isCsrfTokenValid('delete'.$activityUser->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($activityUser);
            $entityManager->flush();
        }
        $this->addFlash("success","Deleted activity user successfully.");

        return $this->redirectToRoute('activity_user_index', ["project" => $project->getId(), "activity" => $activity->getId()]);
    }
}
