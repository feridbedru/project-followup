<?php

namespace App\Controller\Project;

use App\Entity\ProjectVersion;
use App\Entity\Log;
use App\Form\ProjectVersionType;
use App\Repository\ProjectRepository;
use App\Repository\ProjectVersionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('project/{project}/versions')]
class ProjectVersionController extends AbstractController
{
    #[Route('/', name: 'project_version_index', methods: ['GET', 'POST'])]
    public function index(ProjectVersionRepository $projectVersionRepository, ProjectRepository $projectRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        if ($request->request->get('edit')) {

            $id = $request->request->get('edit');
            $projectVersion = $projectVersionRepository->findOneBy(['id' => $id]);
            $original = clone $projectVersion;
            $form = $this->createForm(ProjectVersionType::class, $projectVersion);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $log = new Log();
                $log =  $log->logEvent($request->getClientIp(), $this->getUser(), $projectVersion->getId(), "ProjectVersion", "UPDATE", $original, $projectVersion);
                $entityManager->persist($log);
                $entityManager->flush();
                $this->addFlash("success", "Updated project version successfully.");

                return $this->redirectToRoute('project_version_index', ["project" => $project->getId()]);
            }

            $queryBuilder = $projectVersionRepository->findBy(['project' => $project]);
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 5);

            return $this->render('project/version/index.html.twig', [
                'project_versions' => $data,
                'form' => $form->createView(),
                'edit' => $id,
                'project' => $project
            ]);
        }


        $projectVersion = new ProjectVersion();
        $form = $this->createForm(ProjectVersionType::class, $projectVersion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $projectVersion->setProject($project);
            $projectVersion->setCreatedBy($this->getUser());
            $projectVersion->setCreatedAt(new \DateTime());
            $entityManager->persist($projectVersion);
            $entityManager->flush();

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(), $this->getUser(), $projectVersion->getId(), "ProjectVersion", "CREATE", $projectVersion);
            $entityManager->persist($log);
            $entityManager->flush();
            $this->addFlash("success", "Registered project version successfully.");

            return $this->redirectToRoute('project_version_index', ["project" => $project->getId()]);
        }

        $queryBuilder = $projectVersionRepository->findBy(['project' => $project]);
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 5);

        return $this->render('project/version/index.html.twig', [
            'project_versions' => $data,
            'form' => $form->createView(),
            'edit' => false,
            'project' => $project
        ]);
    }

    #[Route('/{id}', name: 'project_version_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectVersion $projectVersion, ProjectRepository $projectRepository): Response
    {
        $this->denyAccessUnlessGranted('project_version_delete');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        if ($this->isCsrfTokenValid('delete' . $projectVersion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($projectVersion);

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(), $this->getUser(), $projectVersion->getId(), "ProjectVersion", "DELETE", $projectVersion);
            $entityManager->persist($log);
            $entityManager->flush();
        }
        $this->addFlash("success", "Deleted project version successfully.");

        return $this->redirectToRoute('project_version_index', ["project" => $project->getId()]);
    }
}
