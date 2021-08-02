<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Repository\ProjectMembersRepository;
use App\Repository\ProjectMilestoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;  

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(ProjectRepository $projectRepository, Request $request, ProjectMembersRepository $projectMembersRepository,ProjectMilestoneRepository $projectMilestoneRepository): Response
    {
        $projectMember = $projectMembersRepository->findBy(['user' => $this->getUser(), 'status' => 1]);
        $projects = [];
        $project_list = [];
        foreach ($projectMember as $member ) {
            $project = $projectRepository->findOneBy(['id' => $member->getProject()->getId()]);
            // dd($project);
            array_push($projects, $project);
        }
        foreach ($projects as $proj ) {
            if ( ! in_array($proj, $project_list)) {
                $project_list[] = $proj;
            }
        }
        // $activities = $projectMilestoneRepository->findBy(['project'=>$project_list]);
        // dd($milestones);
        $activeProject = $projectRepository->findBy(['id' => $project_list, 'status' => 1]);
        $closedProject = $projectRepository->findBy(['id' => $project_list, 'status' => 2]);
        // dd($closedProject);
        $session = $this->get('session');
        $session->set('myprojects', $project_list);

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'projects' => $project_list,
            'active_project' => $activeProject,
            'closed_project' => $closedProject,
        ]);
    }
}
