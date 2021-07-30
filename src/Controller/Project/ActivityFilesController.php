<?php

namespace App\Controller\Project;

use App\Entity\ActivityFiles;
use App\Entity\Log;
use App\Form\ActivityFilesType;
use App\Repository\ProjectRepository;
use App\Repository\ProjectActivityRepository;
use App\Repository\ActivityFilesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('project/{project}/activity/{activity}/files')]
class ActivityFilesController extends AbstractController
{
    #[Route('/', name: 'activity_files_index', methods: ['GET', 'POST'])]
    public function index(ActivityFilesRepository $activityFilesRepository, ProjectActivityRepository $projectActivityRepository, ProjectRepository $projectRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $activity = $projectActivityRepository->findOneBy(['id' => $request->attributes->get('activity')]);
        if ($request->request->get('edit')) {

            $id = $request->request->get('edit');
            $activityFile = $activityFilesRepository->findOneBy(['id' => $id]);
            $form = $this->createForm(ActivityFilesType::class, $activityFile);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash("success", "Updated activity files successfully.");

                return $this->redirectToRoute('activity_files_index', ["project" => $project->getId(), "activity" => $activity->getId()]);
            }

            $queryBuilder = $activityFilesRepository->findBy(["activity" => $activity]);
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 10);

            return $this->render('project/activity_files/index.html.twig', [
                'activity_files' => $data,
                'form' => $form->createView(),
                'edit' => $id,
                'project' => $project,
                'activity' => $activity,
            ]);
        }


        $activityFile = new ActivityFiles();
        $form = $this->createForm(ActivityFilesType::class, $activityFile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $activityFile->setUploadedAt(new \DateTime());
            $activityFile->setUploadedBy($this->getUser());
            $activityFile->setActivity($activity);
            $uploadedFile = $form['file']->getData();
            $location = $this->getParameter('kernel.project_dir') . '/public/upload/activity';
            $newFilename = uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
            $uploadedFile->move($location, $newFilename);
            $activityFile->setFile($newFilename);
            $entityManager->persist($activityFile);
            $entityManager->flush();
            $this->addFlash("success", "Registered activity files successfully.");

            return $this->redirectToRoute('activity_files_index', ["project" => $project->getId(), "activity" => $activity->getId()]);
        }

        $queryBuilder = $activityFilesRepository->findBy(["activity" => $activity]);
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 10);

        return $this->render('project/activity_files/index.html.twig', [
            'activity_files' => $data,
            'form' => $form->createView(),
            'edit' => false,
            'project' => $project,
            'activity' => $activity,
        ]);
    }

    #[Route('/{id}', name: 'activity_files_delete', methods: ['POST'])]
    public function delete(Request $request, ActivityFiles $activityFile, ProjectActivityRepository $projectActivityRepository, ProjectRepository $projectRepository): Response
    {
        $this->denyAccessUnlessGranted('activity_files_delete');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $activity = $projectActivityRepository->findOneBy(['id' => $request->attributes->get('activity')]);
        if ($this->isCsrfTokenValid('delete' . $activityFile->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($activityFile);
            $entityManager->flush();
        }
        $this->addFlash("success", "Deleted activity files successfully.");

        return $this->redirectToRoute('activity_files_index', ["project" => $project->getId(), "activity" => $activity->getId()]);
    }
}
