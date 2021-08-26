<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Repository\ProjectMembersRepository;
use App\Repository\ActivityUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(ProjectRepository $projectRepository, Request $request, ProjectMembersRepository $projectMembersRepository): Response
    {
        $projectMember = $projectMembersRepository->findBy(['user' => $this->getUser(), 'status' => 1]);
        $projects = [];
        $project_list = [];
        foreach ($projectMember as $member) {
            $project = $projectRepository->findOneBy(['id' => $member->getProject()->getId()]);
            array_push($projects, $project);
        }
        $managingProjects = $projectRepository->findBy(['project_manager' => $this->getUser()]);
        foreach ($managingProjects as $projectr) {
            array_push($projects, $projectr);
        }
        foreach ($projects as $proj) {
            if (!in_array($proj, $project_list)) {
                $project_list[] = $proj;
            }
        }
        $activeProject = $projectRepository->findBy(['id' => $project_list, 'status' => 1]);
        $closedProject = $projectRepository->findBy(['id' => $project_list, 'status' => 2]);
        $session = $this->get('session');
        $session->set('myprojects', $project_list);

        return $this->render('dashboard/index.html.twig', [
            'projects' => $project_list,
            'active_project' => $activeProject,
            'closed_project' => $closedProject,
        ]);
    }

    #[Route('/calendar', name: 'calendar_index')]
    public function calendar(): Response
    {
        return $this->render('dashboard/calendar.html.twig', [
            'controller_name' => 'CalendarController',
        ]);
    }

    #[Route('/myprojects', name: 'my_project')]
    public function myprojects(ProjectRepository $projectRepository, ProjectMembersRepository $projectMembersRepository, Request $request): Response
    {
        $projectMember = $projectMembersRepository->findBy(['user' => $this->getUser(), 'status' => 1]);
        $projects = [];
        $project_list = [];
        foreach ($projectMember as $member ) {
            $project = $projectRepository->findOneBy(['id' => $member->getProject()->getId()]);
            array_push($projects, $project);
        }
        $managingProjects = $projectRepository->findBy(['project_manager' => $this->getUser()]);
        foreach ($managingProjects as $projectr) {
            array_push($projects, $projectr);
        }
        foreach ($projects as $proj ) {
            if ( ! in_array($proj, $project_list)) {
                $project_list[] = $proj;
            }
        }

        $project = NULL;
        $projectId = $request->query->get('project');
        if($projectId){
            $project = $projectRepository->findOneBy(['id' => $projectId]);
        }
        
        return $this->render('dashboard/myprojects.html.twig', [
            'projects' => $project_list,
            'project' => $project,
        ]);
    }

    #[Route('/myprojects/{project}/tasks', name: 'user_activity_index', methods: ['GET'])]
    public function activities(ActivityUserRepository $activityUserRepository, ProjectRepository $projectRepository, ProjectMembersRepository $projectMembersRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('project_activity_index');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $projectMember = $projectMembersRepository->findOneBy(['user' => $this->getUser(), 'status' => 1, 'project' => $project]);
        $user = $projectMember->getId();
        $todo = $activityUserRepository->findBy(['status' => 1, 'user' => $user]);
        $doing = $activityUserRepository->findBy(['status' => 2, 'user' => $user]);
        $done = $activityUserRepository->findBy(['status' => 4, 'user' => $user]);
        return $this->render('dashboard/tasks.html.twig', [
            'project' => $project,
            'todo_list' => $todo,
            'doing_list' => $doing,
            'done_list' => $done,
        ]);
    }
}
