<?php

namespace App\Controller\Project;

use App\Entity\ProjectVersion;
use App\Entity\Log;
use App\Form\ProjectVersionType;
use App\Repository\ProjectVersionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/versions')]
class ProjectVersionController extends AbstractController
{
    #[Route('/', name: 'project_version_index', methods: ['GET', 'POST'])]
    public function index(ProjectVersionRepository $projectVersionRepository, PaginatorInterface $paginator, Request $request): Response
    {
        if($request->request->get('edit')){
            
            $id = $request->request->get('edit');
            $projectVersion = $projectVersionRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(ProjectVersionType::class, $projectVersion);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash("success","Updated project version successfully.");

                return $this->redirectToRoute('project_version_index');
            }

            $queryBuilder = $projectVersionRepository->findProjectVersion($request->query->get('search'));
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 5 );

            return $this->render('project/version/index.html.twig', [
                'project_versions' => $data,
                'form' => $form->createView(),
                'edit' => $id
            ]);
        }

        
        $projectVersion = new ProjectVersion();
        $form = $this->createForm(ProjectVersionType::class, $projectVersion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($projectVersion);
            $entityManager->flush();
            $this->addFlash("success","Registered project version successfully.");

            return $this->redirectToRoute('project_version_index');
        }

        $queryBuilder = $projectVersionRepository->findProjectVersion($request->query->get('search'));
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 5 );

        return $this->render('project/version/index.html.twig', [
            'project_versions' => $data,
            'form' => $form->createView(),
            'edit' => false
        ]);

    }

    #[Route('/{id}', name: 'project_version_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectVersion $projectVersion): Response
    {
        $this->denyAccessUnlessGranted('project_version_delete');
        if ($this->isCsrfTokenValid('delete'.$projectVersion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($projectVersion);
            $entityManager->flush();
        }
        $this->addFlash("success","Deleted project version successfully.");

        return $this->redirectToRoute('project_version_index');
    }
}
