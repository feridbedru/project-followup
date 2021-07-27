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

#[Route('/project/{project}/resources')]
class ProjectResourceController extends AbstractController
{
    #[Route('/', name: 'project_resource_index', methods: ['GET'])]
    public function index(ProjectResourceRepository $projectResourceRepository, ProjectRepository $projectRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $this->denyAccessUnlessGranted('project_resource_index');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $projectResources = $paginator->paginate($projectResourceRepository->findBy(['project' => $request->attributes->get('project')]), $request->query->getInt('page', 1), 10);
        return $this->render('project/resource/index.html.twig', [
            'project_resources' => $projectResources,
            'project' => $project,
        ]);
    }

    #[Route('/new', name: 'project_resource_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProjectRepository $projectRepository): Response
    {
        $this->denyAccessUnlessGranted('project_resource_create');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
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
            $this->addFlash("success", "created project resource successfully.");

            return $this->redirectToRoute('project_resource_index', ["project" => $project->getId()]);
        }

        return $this->render('project/resource/new.html.twig', [
            'project_resource' => $projectResource,
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }

    #[Route('/{id}', name: 'project_resource_show', methods: ['GET'])]
    public function show(ProjectResource $projectResource, ProjectRepository $projectRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('project_resource_show');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        return $this->render('project/resource/show.html.twig', [
            'project_resource' => $projectResource,
            'project' => $project,
        ]);
    }

    #[Route('/{id}/edit', name: 'project_resource_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProjectResource $projectResource, ProjectRepository $projectRepository): Response
    {
        $this->denyAccessUnlessGranted('project_resource_edit');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $form = $this->createForm(ProjectResourceType::class, $projectResource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form['file']->getData();
            $location = $this->getParameter('kernel.project_dir') . '/public/upload/resource';
            $newFilename = uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
            $uploadedFile->move($location, $newFilename);
            $projectResource->setFile($newFilename);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success", "Updated project resource successfully.");

            return $this->redirectToRoute('project_resource_index', ["project" => $project->getId()]);
        }

        return $this->render('project/resource/edit.html.twig', [
            'project_resource' => $projectResource,
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }

    #[Route('/{id}', name: 'project_resource_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectResource $projectResource, ProjectRepository $projectRepository): Response
    {
        $this->denyAccessUnlessGranted('project_resource_delete');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        if ($this->isCsrfTokenValid('delete' . $projectResource->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($projectResource);
            $entityManager->flush();
        }

        $this->addFlash("success", "Deleted project resource successfully.");

        return $this->redirectToRoute('project_resource_index', ["project" => $project->getId()]);
    }
}
