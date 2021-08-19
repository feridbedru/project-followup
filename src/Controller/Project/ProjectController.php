<?php

namespace App\Controller\Project;

use App\Entity\Project;
use App\Entity\Log;
use App\Entity\ProjectPlanRevision;
use App\Entity\ProjectPlanStatus;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Repository\EmailTemplateRepository;
use App\Repository\ProjectResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use App\Services\MailerService;

#[Route('/project')]
class ProjectController extends AbstractController
{
    #[Route('/', name: 'project_index', methods: ['GET'])]
    public function index(ProjectRepository $projectRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $this->denyAccessUnlessGranted('project_index');
        $projects = $paginator->paginate($projectRepository->findAll(), $request->query->getInt('page', 1), 10);
        return $this->render('project/index.html.twig', [
            'projects' => $projects,
        ]);
    }
    
    #[Route('/{id}/dashboard', name: 'project_dashboard', methods: ['GET'])]
    public function dashboard(Project $project): Response
    {
        $this->denyAccessUnlessGranted('project_show');
        return $this->render('project/dashboard.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/new', name: 'project_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('project_create');
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $project->setStatus(1);
            $project->setCreatedBy($this->getUser());
            $project->setCreatedAt(new \DateTime());
            $entityManager->persist($project);
            $entityManager->flush();
            $this->addFlash("success","created project successfully.");

            return $this->redirectToRoute('project_show', ["id" => $project->getId()]);
        }

        return $this->render('project/new.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/show', name: 'project_show', methods: ['GET','POST'])]
    public function show(Project $project, ProjectResourceRepository $projectResourceRepository): Response
    {
        $this->denyAccessUnlessGranted('project_show');
        $projectResource = $projectResourceRepository->findBy(['project'=>$project]);
        return $this->render('project/show.html.twig', [
            'project' => $project,
            'resources' => $projectResource,
        ]);
    }
    #[Route('/{id}/status', name: 'plan_approve_request', methods: ['POST'])]
    public function requestApproval( Project $project, ProjectRepository $projectRepository, Request $request, MailerInterface $mailer, MailerService $mservice, EmailTemplateRepository $emailTemplateRepository)
    {
        $revision_id =  $request->request->get('revision_id');
        $revision_detail = $request->request->get('revision_detail');
        $entityManager = $this->getDoctrine()->getManager();
        $projectPlanRevision = new ProjectPlanRevision();
        $projectPlanRevision->setProject($project);
        $projectPlanRevision->setRevisionId($revision_id);
        $projectPlanRevision->setRevisionDetails($revision_detail);
        $projectPlanRevision->setCreatedAt(new \DateTime());
        $projectPlanRevision->setCreatedBy($this->getUser());
        $entityManager->persist($projectPlanRevision);
        $entityManager->flush();
        $project->setStatus(2);
        $this->getDoctrine()->getManager()->flush();
        $emailTemplate = $emailTemplateRepository->findOneBy(['code' => 'project_plan_submitted']);
        $message = $emailTemplate->getContent();
        $projectName = $project->getName();
        $message = str_replace('$project', $projectName, $message);
        $recievers = array();
        $reciever = $project->getUnit()->getHead()->getEmail();
        array_push($recievers, $reciever);
        $mservice->sendEmail($mailer, $recievers, $emailTemplate->getName(), $message);

        $this->addFlash("success", "Project plan submitted successfully.");
        return $this->redirectToRoute('project_show', ["id" => $project->getId()]);
    }

    #[Route('/{id}/approve', name: 'plan_approve_reject', methods: ['POST'])]
    public function approvePlan( Project $project, ProjectRepository $projectRepository, Request $request, MailerInterface $mailer, MailerService $mservice, EmailTemplateRepository $emailTemplateRepository)
    {
        $decision = $request->request->get('decision');
        $justification = $request->request->get('justification');
        $entityManager = $this->getDoctrine()->getManager();
        $projectPlanStatus = new ProjectPlanStatus();
        $projectPlanStatus->setProject($project);
        $projectPlanStatus->setJustification($justification);
        $projectPlanStatus->setDecision($decision);
        $projectPlanStatus->setCreatedBy($this->getUser());
        $projectPlanStatus->setCreatedAt(new \DateTime());
        $entityManager->persist($projectPlanStatus);
        $entityManager->flush();
        $project->setStatus($decision);
        $this->getDoctrine()->getManager()->flush();
        $status = '';
        if($decision == 1){
            $status = 'REJECTED';
        }
        else{
            $status = 'APPROVED';
        }
        $emailTemplate = $emailTemplateRepository->findOneBy(['code' => 'project_plan_status_update']);
        $message = $emailTemplate->getContent();
        $projectName = $project->getName();
        $message = str_replace('$project', $projectName, $message);
        $stat = '';
        if ($decision == 1) {
            $stat = $status. ' for the following reason. '.$justification;
        }
        else{
            $stat = $status;
        }
        $message = str_replace('$status', $stat, $message);
        $recievers = array();
        $reciever1 = $project->getUnit()->getHead()->getEmail();
        array_push($recievers, $reciever1);
        $reciever2 = $project->getProjectManager()->getEmail();
        array_push($recievers, $reciever2);
        $mservice->sendEmail($mailer, $recievers, $emailTemplate->getName(), $message);

        $this->addFlash("success", "Project plan $status successfully.");
        return $this->redirectToRoute('project_show', ["id" => $project->getId()]);
    }

    #[Route('/{id}/implementation', name: 'project_start_implementation', methods: ['POST'])]
    public function startImplementation( Project $project, ProjectRepository $projectRepository, Request $request, MailerInterface $mailer, MailerService $mservice, EmailTemplateRepository $emailTemplateRepository)
    {
        $decision = $request->request->get('decision');
        $entityManager = $this->getDoctrine()->getManager();
        $projectPlanStatus = new ProjectPlanStatus();
        $projectPlanStatus->setProject($project);
        $projectPlanStatus->setDecision($decision);
        $projectPlanStatus->setCreatedBy($this->getUser());
        $projectPlanStatus->setCreatedAt(new \DateTime());
        $entityManager->persist($projectPlanStatus);
        $entityManager->flush();
        $project->setStatus($decision);
        $this->getDoctrine()->getManager()->flush();
        $emailTemplate = $emailTemplateRepository->findOneBy(['code' => 'project_implementation_started']);
        $message = $emailTemplate->getContent();
        $projectName = $project->getName();
        $message = str_replace('$project', $projectName, $message);
        $recievers = array();
        $reciever1 = $project->getUnit()->getHead()->getEmail();
        array_push($recievers, $reciever1);
        $reciever2 = $project->getProjectManager()->getEmail();
        array_push($recievers, $reciever2);
        $mservice->sendEmail($mailer, $recievers, $emailTemplate->getName(), $message);

        $this->addFlash("success", "Started project implementation successfully.");
        return $this->redirectToRoute('project_show', ["id" => $project->getId()]);
    }
    #[Route('/{id}/edit', name: 'project_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Project $project): Response
    {
        $this->denyAccessUnlessGranted('project_edit');
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success","Updated project successfully.");

            return $this->redirectToRoute('project_index');
        }

        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'project_delete', methods: ['POST'])]
    public function delete(Request $request, Project $project): Response
    {
        $this->denyAccessUnlessGranted('project_delete');
        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($project);
            $entityManager->flush();
        }

        $this->addFlash("success","Deleted project successfully.");

        return $this->redirectToRoute('project_index');
    }
}
