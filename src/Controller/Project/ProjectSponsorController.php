<?php

namespace App\Controller\Project;

use App\Entity\ProjectSponsor;
use App\Entity\Log;
use App\Form\ProjectSponsorType;
use App\Repository\ProjectSponsorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/sponsors')]
class ProjectSponsorController extends AbstractController
{
    #[Route('/', name: 'project_sponsor_index', methods: ['GET', 'POST'])]
    public function index(ProjectSponsorRepository $projectSponsorRepository, PaginatorInterface $paginator, Request $request): Response
    {
        if($request->request->get('edit')){
            
            $id = $request->request->get('edit');
            $projectSponsor = $projectSponsorRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(ProjectSponsorType::class, $projectSponsor);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash("success","Updated project_sponsor successfully.");

                return $this->redirectToRoute('project_sponsor_index');
            }

            $queryBuilder = $projectSponsorRepository->findAll();
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

            return $this->render('project/sponsor/index.html.twig', [
                'project_sponsors' => $data,
                'form' => $form->createView(),
                'edit' => $id
            ]);
        }

        
        $projectSponsor = new ProjectSponsor();
        $form = $this->createForm(ProjectSponsorType::class, $projectSponsor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($projectSponsor);
            $entityManager->flush();
            $this->addFlash("success","Registered project_sponsor successfully.");

            return $this->redirectToRoute('project_sponsor_index');
        }

        $queryBuilder = $projectSponsorRepository->findAll();
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

        return $this->render('project/sponsor/index.html.twig', [
            'project_sponsors' => $data,
            'form' => $form->createView(),
            'edit' => false
        ]);

    }

    #[Route('/{id}', name: 'project_sponsor_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectSponsor $projectSponsor): Response
    {
        $this->denyAccessUnlessGranted('project_sponsor_delete');
        if ($this->isCsrfTokenValid('delete'.$projectSponsor->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($projectSponsor);
            $entityManager->flush();
        }
        $this->addFlash("success","Deleted project_sponsor successfully.");

        return $this->redirectToRoute('project_sponsor_index');
    }
}
