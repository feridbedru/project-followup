<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Repository\ProjectMembersRepository;
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
        // dd($projectMember);
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

        $myproject = $projectRepository->findById(array($project_list));

        $session = $this->get('session');
        $session->set('myprojects', $project_list);
        // $this->get('twig')->addGlobal('myprojects', $project_list);

        // $twig = new \Twig\Environment($loader);
        // $twig->addGlobal('projects', $myproject);
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'projects' => $project_list,
        ]);
    }
}
