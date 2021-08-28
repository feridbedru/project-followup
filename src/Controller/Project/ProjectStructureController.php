<?php

namespace App\Controller\Project;

use App\Entity\ProjectStructure;
use App\Entity\Log;
use App\Form\ProjectStructureType;
use App\Repository\ProjectRepository;
use App\Repository\ProjectStructureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/project/{project}/structure')]
class ProjectStructureController extends AbstractController
{
    #[Route('/', name: 'project_structure_index', methods: ['GET', 'POST'])]
    public function index(ProjectStructureRepository $projectStructureRepository, ProjectRepository $projectRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        if($request->request->get('edit')){
            
            $id = $request->request->get('edit');
            $projectStructure = $projectStructureRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(ProjectStructureType::class, $projectStructure, array('project' => $project->getId()));
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash("success","Updated project structure successfully.");

                return $this->redirectToRoute('project_structure_index', ["project" => $project->getId()]);
            }

            $queryBuilder = $projectStructureRepository->findBy(['project' => $project]);
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

            return $this->render('project/structure/index.html.twig', [
                'project_structures' => $data,
                'form' => $form->createView(),
                'edit' => $id,
                'project' => $project
            ]);
        }

        
        $projectStructure = new ProjectStructure();
        $form = $this->createForm(ProjectStructureType::class, $projectStructure, array('project' => $project->getId()));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $projectStructure->setCreatedAt(new \DateTime());
            $projectStructure->setCreatedBy($this->getUser());
            $projectStructure->setProject($project);
            $entityManager->persist($projectStructure);
            $entityManager->flush();
            $this->addFlash("success","Registered project structure successfully.");

            return $this->redirectToRoute('project_structure_index', ["project" => $project->getId()]);
        }

        $queryBuilder = $projectStructureRepository->findBy(['project' => $project]);
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

        return $this->render('project/structure/index.html.twig', [
            'project_structures' => $data,
            'form' => $form->createView(),
            'edit' => false,
            'project' => $project,
        ]);

    }

    #[Route('/{id}', name: 'project_structure_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectRepository $projectRepository, ProjectStructure $projectStructure): Response
    {
        $this->denyAccessUnlessGranted('project_structure_delete');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        if ($this->isCsrfTokenValid('delete'.$projectStructure->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($projectStructure);
            $entityManager->flush();
        }
        $this->addFlash("success","Deleted project structure successfully.");

        return $this->redirectToRoute('project_structure_index', ["project" => $project->getId()]);
    }
}
