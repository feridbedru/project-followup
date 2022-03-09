<?php

namespace App\Controller\Project;

use App\Entity\ProjectResource;
use App\Entity\Log;
use App\Form\ProjectResourceType;
use App\Repository\ProjectRepository;
use App\Repository\ProjectResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Services\ProjectAccessService;

#[Route('/project/{project}/resources')]
class ProjectResourceController extends AbstractController
{
    #[Route('/', name: 'project_resource_index', methods: ['GET', 'POST'])]
    public function index(ProjectResourceRepository $projectResourceRepository, PaginatorInterface $paginator, Request $request, ProjectRepository $projectRepository, ProjectAccessService $projectAccessService): Response
    {
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $projectAccessService->canUserAccessProject($this->getUser(), $project);
        if($request->request->get('edit')){
            $id = $request->request->get('edit');
            $projectResource = $projectResourceRepository->findOneBy(['id'=>$id]);
            $original = clone $projectResource;
            $form = $this->createForm(ProjectResourceType::class, $projectResource);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $log = new Log();
                $log =  $log->logEvent($request->getClientIp(), $this->getUser(), $projectResource->getId(), "ProjectResource", "UPDATE", $original, $projectResource);
                $entityManager->persist($log);
                $entityManager->flush();
                $this->addFlash("success","Updated project_resource successfully.");

                return $this->redirectToRoute('project_resource_index', ["project" => $project->getId()]);
            }

            $queryBuilder = $projectResourceRepository->findBy(['project' => $request->attributes->get('project')]);
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

            return $this->render('project/resource/index.html.twig', [
                'project_resources' => $data,
                'form' => $form->createView(),
                'edit' => $id,
                'project' => $project,
            ]);
        }

        
        $projectResource = new ProjectResource();
        $form = $this->createForm(ProjectResourceType::class, $projectResource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $projectResource->setProject($project);
            $projectResource->setUploadedBy($this->getUser());
            $projectResource->setUploadedAt(new \DateTime());
            $uploadedFile = $form['file']->getData();
            $location = $this->getParameter('kernel.project_dir') . '/public/upload/resource';
            $newFilename = uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
            $uploadedFile->move($location, $newFilename);
            $projectResource->setFile($newFilename);
            $entityManager->persist($projectResource);
            $entityManager->flush();

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(), $this->getUser(), $projectResource->getId(), "ProjectResource", "CREATE", $projectResource);
            $entityManager->persist($log);
            $entityManager->flush();

            $this->addFlash("success","Registered project resource successfully.");

            return $this->redirectToRoute('project_resource_index', ["project" => $project->getId()]);
        }

        $queryBuilder = $projectResourceRepository->findBy(['project' => $request->attributes->get('project')]);
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

        return $this->render('project/resource/index.html.twig', [
            'project_resources' => $data,
            'form' => $form->createView(),
            'edit' => false,
            'project' => $project,
        ]);

    }

    #[Route('/{id}', name: 'project_resource_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectResource $projectResource, ProjectRepository $projectRepository, ProjectAccessService $projectAccessService): Response
    {
        $this->denyAccessUnlessGranted('project_resource_delete');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $projectAccessService->canUserAccessProject($this->getUser(), $project);
        if ($this->isCsrfTokenValid('delete'.$projectResource->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($projectResource);

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(), $this->getUser(), $projectResource->getId(), "ProjectResource", "DELETE", $projectResource);
            $entityManager->persist($log);
            $entityManager->flush();
        }
        $this->addFlash("success","Deleted project resource successfully.");

        return $this->redirectToRoute('project_resource_index', ["project" => $project->getId()]);
    }
}
