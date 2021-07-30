<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Log;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/program')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'program_index', methods: ['GET'])]
    public function index(ProgramRepository $programRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $this->denyAccessUnlessGranted('program_index');
        $programs = $paginator->paginate($programRepository->findAll(), $request->query->getInt('page', 1), 10);
        return $this->render('program/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    #[Route('/new', name: 'program_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('program_create');
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($program);
            $entityManager->flush();
            $this->addFlash("success","created program successfully.");

            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/new.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'program_show', methods: ['GET'])]
    public function show(Program $program, ProjectRepository $projectRepository): Response
    {
        $this->denyAccessUnlessGranted('program_show');
        $project = $projectRepository->findBy(['program' => $program]);
        return $this->render('program/show.html.twig', [
            'program' => $program,
            'projects' => $project,
        ]);
    }

    #[Route('/{id}/edit', name: 'program_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Program $program): Response
    {
        $this->denyAccessUnlessGranted('program_edit');
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success","Updated program successfully.");

            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'program_delete', methods: ['POST'])]
    public function delete(Request $request, Program $program): Response
    {
        $this->denyAccessUnlessGranted('program_delete');
        if ($this->isCsrfTokenValid('delete'.$program->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($program);
            $entityManager->flush();
        }

        $this->addFlash("success","Deleted program successfully.");

        return $this->redirectToRoute('program_index');
    }
}
