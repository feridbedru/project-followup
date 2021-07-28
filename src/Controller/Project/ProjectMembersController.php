<?php

namespace App\Controller\Project;

use App\Entity\ProjectMembers;
use App\Entity\Log;
use App\Form\ProjectMembersType;
use App\Repository\ProjectRepository;
use App\Repository\ProjectMembersRepository;
use App\Repository\ProjectTeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('project/{project}/team/{team}/members')]
class ProjectMembersController extends AbstractController
{
    #[Route('/', name: 'project_members_index', methods: ['GET', 'POST'])]
    public function index(ProjectMembersRepository $projectMembersRepository, ProjectTeamRepository $projectTeamRepository, ProjectRepository $projectRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $team = $projectTeamRepository->findOneBy(['id' => $request->attributes->get('team')]);
        if ($request->request->get('edit')) {

            $id = $request->request->get('edit');
            $projectMember = $projectMembersRepository->findOneBy(['id' => $id]);
            $form = $this->createForm(ProjectMembersType::class, $projectMember);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash("success", "Updated project members successfully.");

                return $this->redirectToRoute('project_members_index', ["team" => $team->getId(), "project" => $project->getId()]);
            }

            $queryBuilder = $projectMembersRepository->findBy(['team' => $team]);
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 10);

            return $this->render('project/members/index.html.twig', [
                'project_members' => $data,
                'form' => $form->createView(),
                'edit' => $id,
                'team' => $team,
                'project' => $project
            ]);
        }


        $projectMember = new ProjectMembers();
        $form = $this->createForm(ProjectMembersType::class, $projectMember);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $projectMember->setIsWorkingOnTask(0);
            $projectMember->setCreatedBy($this->getUser());
            $projectMember->setCreatedAt(new \DateTime());
            $projectMember->setTeam($team);
            $entityManager->persist($projectMember);
            $entityManager->flush();
            $this->addFlash("success", "Registered project members successfully.");

            return $this->redirectToRoute('project_members_index', ["team" => $team->getId(), "project" => $project->getId()]);
        }

        $queryBuilder = $projectMembersRepository->findBy(['team' => $team]);
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 10);

        return $this->render('project/members/index.html.twig', [
            'project_members' => $data,
            'form' => $form->createView(),
            'edit' => false,
            'team' => $team,
            'project' => $project
        ]);
    }

    #[Route('/{id}', name: 'project_members_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectTeamRepository $projectTeamRepository, ProjectRepository $projectRepository, ProjectMembers $projectMember): Response
    {
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $team = $projectTeamRepository->findOneBy(['id' => $request->attributes->get('team')]);

        $this->denyAccessUnlessGranted('project_members_delete');
        if ($this->isCsrfTokenValid('delete' . $projectMember->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($projectMember);
            $entityManager->flush();
        }
        $this->addFlash("success", "Deleted project members successfully.");

        return $this->redirectToRoute('project_members_index', ["team" => $team->getId(), "project" => $project->getId()]);
    }
}
