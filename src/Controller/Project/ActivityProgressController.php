<?php

namespace App\Controller\Project;

use App\Entity\ActivityProgress;
use App\Entity\Log;
use App\Form\ActivityProgressType;
use App\Repository\ProjectRepository;
use App\Repository\ProjectActivityRepository;
use App\Repository\ActivityProgressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('project/{project}/activity/{activity}/progress')]
class ActivityProgressController extends AbstractController
{
    #[Route('/', name: 'activity_progress_index', methods: ['GET', 'POST'])]
    public function index(ActivityProgressRepository $activityProgressRepository, ProjectActivityRepository $projectActivityRepository, ProjectRepository $projectRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $activity = $projectActivityRepository->findOneBy(['id' => $request->attributes->get('activity')]);
        // dd($activity);
        $report_days = array();
        $days = $activityProgressRepository->findReportDays($activity);
        // dd($days);
        foreach ($days as $day) {
            // $date = new \DateTime($day['created_at']);
            $date = $day['created_at']->format('Y-m-d H:i:s');
            // $date = date("Y-m-d", strtotime((string)$day['created_at']));
            // $date = new \DateTime(date_format($day['created_at'], 'Y-m-d'));
            // dd($date);
            $report = $activityProgressRepository->findReport($date);
            // dd($report);
            $report_days[date_format($day['created_at'], 'Y-m-d')] = $report;
        }
// dd($report_days);
        if ($request->request->get('edit')) {

            $id = $request->request->get('edit');
            $activityProgress = $activityProgressRepository->findOneBy(['id' => $id]);
            $form = $this->createForm(ActivityProgressType::class, $activityProgress);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash("success", "Updated activity progress successfully.");

                return $this->redirectToRoute('activity_progress_index', ["project" => $project->getId(), "activity" => $activity->getId()]);
            }
            $queryBuilder = $activityProgressRepository->findBy(["activity" => $activity]);
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 10);

            return $this->render('project/activity_progress/index.html.twig', [
                'activity_progresses' => $data,
                'form' => $form->createView(),
                'edit' => $id,
                'project' => $project,
                'activity' => $activity,
                'report_days' => $report_days,
            ]);
        }


        $activityProgress = new ActivityProgress();
        $form = $this->createForm(ActivityProgressType::class, $activityProgress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $activityProgress->setActivity($activity);
            $activityProgress->setCreatedAt(new \DateTime());
            $activityProgress->setCreatedBy($this->getUser());
            $uploadedFile = $form['file']->getData();
            if($uploadedFile){
                $location = $this->getParameter('kernel.project_dir') . '/public/upload/progress';
                $newFilename = uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
                $uploadedFile->move($location, $newFilename);
                $activityProgress->setFile($newFilename);
            }
            $entityManager->persist($activityProgress);
            $entityManager->flush();
            $this->addFlash("success", "Registered activity progress successfully.");

            return $this->redirectToRoute('activity_progress_index', ["project" => $project->getId(), "activity" => $activity->getId()]);
        }

        $queryBuilder = $activityProgressRepository->findBy(["activity" => $activity]);
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 10);

        return $this->render('project/activity_progress/index.html.twig', [
            'activity_progresses' => $data,
            'form' => $form->createView(),
            'edit' => false,
            'project' => $project,
            'activity' => $activity,
            'report_days' => $report_days,
        ]);
    }

    #[Route('/{id}', name: 'activity_progress_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectActivityRepository $projectActivityRepository, ProjectRepository $projectRepository, ActivityProgress $activityProgress): Response
    {
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $activity = $projectActivityRepository->findOneBy(['id' => $request->attributes->get('activity')]);
        $this->denyAccessUnlessGranted('activity_progress_delete');
        if ($this->isCsrfTokenValid('delete' . $activityProgress->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($activityProgress);
            $entityManager->flush();
        }
        $this->addFlash("success", "Deleted activity progress successfully.");

        return $this->redirectToRoute('activity_progress_index', ["project" => $project->getId(), "activity" => $activity->getId()]);
    }
}