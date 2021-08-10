<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Repository\ProjectMembersRepository;
use App\Repository\ActivityUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/myprojects')]
class MyProjectController extends AbstractController
{
    #[Route('/', name: 'my_project')]
    public function index(ProjectRepository $projectRepository, ProjectMembersRepository $projectMembersRepository, Request $request): Response
    {
        $projectMember = $projectMembersRepository->findBy(['user' => $this->getUser(), 'status' => 1]);
        $projects = [];
        $project_list = [];
        foreach ($projectMember as $member ) {
            $project = $projectRepository->findOneBy(['id' => $member->getProject()->getId()]);
            array_push($projects, $project);
        }
        foreach ($projects as $proj ) {
            if ( ! in_array($proj, $project_list)) {
                $project_list[] = $proj;
            }
        }

        $project = NULL;
        $projectId = $request->attributes->get('project');
        if($projectId){
            $project = $projectRepository->findOneBy(['id' => $projectId]);
        }
        
        return $this->render('my_project/index.html.twig', [
            'projects' => $project_list,
            'project' => $project,
        ]);
    }

    #[Route('/{project}/tasks', name: 'user_activity_index', methods: ['GET'])]
    public function activities(ActivityUserRepository $activityUserRepository, ProjectRepository $projectRepository, ProjectMembersRepository $projectMembersRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('project_activity_index');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $projectMember = $projectMembersRepository->findOneBy(['user' => $this->getUser(), 'status' => 1, 'project' => $project]);
        $user = $projectMember->getId();
        $todo = $activityUserRepository->findBy(['status' => 1, 'user' => $user]);
        $doing = $activityUserRepository->findBy(['status' => 2, 'user' => $user]);
        $done = $activityUserRepository->findBy(['status' => 4, 'user' => $user]);
        return $this->render('my_project/tasks.html.twig', [
            'project' => $project,
            'todo_list' => $todo,
            'doing_list' => $doing,
            'done_list' => $done,
        ]);
    }
}
