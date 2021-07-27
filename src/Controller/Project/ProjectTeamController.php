<?php

namespace App\Controller\Project;

use App\Entity\ProjectTeam;
use App\Entity\Log;
use App\Form\ProjectTeamType;
use App\Repository\ProjectRepository;
use App\Repository\ProjectTeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('project/{project}/teams')]
class ProjectTeamController extends AbstractController
{
    #[Route('/', name: 'project_team_index', methods: ['GET', 'POST'])]
    public function index(ProjectTeamRepository $projectTeamRepository, ProjectRepository $projectRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        if ($request->request->get('edit')) {

            $id = $request->request->get('edit');
            $projectTeam = $projectTeamRepository->findOneBy(['id' => $id]);
            $form = $this->createForm(ProjectTeamType::class, $projectTeam);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash("success", "Updated project team successfully.");

                return $this->redirectToRoute('project_team_index', ["project" => $project->getId()]);
            }

            $queryBuilder = $projectTeamRepository->findBy(['project'=>$request->attributes->get('project')]);
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 10);

            return $this->render('project/team/index.html.twig', [
                'project_teams' => $data,
                'form' => $form->createView(),
                'edit' => $id,
                'project' => $project,
            ]);
        }


        $projectTeam = new ProjectTeam();
        $form = $this->createForm(ProjectTeamType::class, $projectTeam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $projectTeam->setProject($project);
            $projectTeam->setCreatedAt(new \DateTime());
            $projectTeam->setCreatedBy($this->getUser());
            $entityManager->persist($projectTeam);
            $entityManager->flush();
            $this->addFlash("success", "Registered project team successfully.");

            return $this->redirectToRoute('project_team_index', ["project" => $project->getId()]);
        }

        $queryBuilder = $projectTeamRepository->findBy(['project'=>$request->attributes->get('project')]);
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 10);

        return $this->render('project/team/index.html.twig', [
            'project_teams' => $data,
            'form' => $form->createView(),
            'edit' => false,
            'project' => $project,
        ]);
    }

    #[Route('/{id}', name: 'project_team_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectTeam $projectTeam, ProjectRepository $projectRepository): Response
    {
        $this->denyAccessUnlessGranted('project_team_delete');
        $project = $projectRepository->findOneBy(['project'=>$request->attributes->get('project')]);
        if ($this->isCsrfTokenValid('delete' . $projectTeam->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($projectTeam);
            $entityManager->flush();
        }

        $this->addFlash("success", "Deleted project team successfully.");

        return $this->redirectToRoute('project_team_index', ["project" => $project->getId()]);
    }
}
