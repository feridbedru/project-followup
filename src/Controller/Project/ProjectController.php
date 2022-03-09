<?php

namespace App\Controller\Project;

use App\Entity\Project;
use App\Entity\Log;
use App\Entity\PlanModificationRequest;
use App\Entity\ProjectPlanRevision;
use App\Entity\ProjectPlanStatus;
use App\Entity\ProjectPlanComment;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Repository\EmailTemplateRepository;
use App\Repository\PlanModificationRequestRepository;
use App\Repository\ProjectPlanCommentRepository;
use App\Repository\ProjectResourceRepository;
use App\Repository\ProjectPlanRevisionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use App\Services\MailerService;
use App\Services\ProjectAccessService;

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
    public function dashboard(Project $project, ProjectAccessService $projectAccessService): Response
    {
        $this->denyAccessUnlessGranted('project_show');
        $projectAccessService->canUserAccessProject($this->getUser(), $project);
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

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(), $this->getUser(), $project->getId(), "Project", "CREATE", $project);
            $entityManager->persist($log);
            $entityManager->flush();
            $this->addFlash("success", "created project successfully.");

            return $this->redirectToRoute('project_show', ["id" => $project->getId()]);
        }

        return $this->render('project/new.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/show', name: 'project_show', methods: ['GET', 'POST'])]
    public function show(Project $project, ProjectResourceRepository $projectResourceRepository, ProjectPlanCommentRepository $projectPlanCommentRepository, PlanModificationRequestRepository $planModificationRequestRepository, ProjectAccessService $projectAccessService): Response
    {
        $this->denyAccessUnlessGranted('project_show');
        $projectAccessService->canUserAccessProject($this->getUser(), $project);
        $projectResource = $projectResourceRepository->findBy(['project' => $project]);
        $comments = $projectPlanCommentRepository->findBy(['project' => $project]);
        $modificationRequest = $planModificationRequestRepository->findOneBy(['project' => $project, 'status' => 1]);
        return $this->render('project/show.html.twig', [
            'project' => $project,
            'resources' => $projectResource,
            'comments' => $comments,
            'mod' => $modificationRequest
        ]);
    }
    #[Route('/{id}/status', name: 'plan_approve_request', methods: ['POST'])]
    public function requestApproval(Project $project, ProjectPlanRevisionRepository $projectPlanRevisionRepository, ProjectRepository $projectRepository, Request $request, MailerInterface $mailer, MailerService $mservice, EmailTemplateRepository $emailTemplateRepository, ProjectAccessService $projectAccessService)
    {
        $projectAccessService->canUserAccessProject($this->getUser(), $project);
        $max = $projectPlanRevisionRepository->findMax($project);
        $max_rev = $max['max'];
        if ($max_rev == null) {
            $max_rev = 0;
        }
        $revision_id = '';
        $revision_type = $request->request->get('revision_type');
        if ($revision_type == 2) {
            $revision_id = $max_rev + 1;
            $revision_id = round($revision_id);
        } else {
            $revision_id = $max_rev + 0.1;
        }
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

    #[Route('/{id}/approve', name: 'plan_approve_reject', methods: ['POST', 'GET'])]
    public function approvePlan(Project $project, ProjectRepository $projectRepository, ProjectPlanRevisionRepository $projectPlanRevisionRepository, ProjectPlanCommentRepository $projectPlanCommentRepository, Request $request, MailerInterface $mailer, MailerService $mservice, EmailTemplateRepository $emailTemplateRepository, ProjectAccessService $projectAccessService)
    {
        $projectAccessService->canUserAccessProject($this->getUser(), $project);
        $lastRevision = $projectPlanRevisionRepository->findLastRevisionDate($project);
        $justification = '';
        $specific_comments = array();
        if ($lastRevision != null) {
            $lastDate = $lastRevision['created_at']->format('Y-m-d H:i:s');
            $comments = $projectPlanCommentRepository->findComments($project, $lastDate);
            foreach ($comments as $comment) {
                $title = $comment->getData();
                $entity = $comment->getEntity();
                $on = '';
                if ($entity == 1) {
                    $on = 'Milestone';
                } elseif ($entity == 2) {
                    $on = 'Deliverable';
                } elseif ($entity == 3) {
                    $on = 'Activity';
                } else {
                    $on = 'Project Component';
                }
                $feedback = $comment->getComment();
                $msg = 'On ' . $on . ' named ' . $title . ', ' . $feedback;
                array_push($specific_comments, $msg);
            }
            
            $justification = $request->request->get('justification');
        }
        $decision = $request->request->get('decision');
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
        if ($decision == 1) {
            $status = 'REJECTED';
        } else {
            $status = 'APPROVED';
        }
        $emailTemplate = $emailTemplateRepository->findOneBy(['code' => 'project_plan_status_update']);
        $message = $emailTemplate->getContent();
        $projectName = $project->getName();
        $message = str_replace('$project', $projectName, $message);
        $stat = '';
        if ($decision == 1) {
            $stat = $status . ' for the following reasons. ' . $justification . ' \n' . implode(",", $specific_comments);
        } else {
            $stat = $status;
        }
        $message = str_replace('$status', $stat, $message);
        $recievers = array();
        if($project->getUnit()->getHead()){
            $reciever1 = $project->getUnit()->getHead()->getEmail();
            array_push($recievers, $reciever1);
        }
        if($project->getProjectManager()){
            $reciever2 = $project->getProjectManager()->getEmail();
            array_push($recievers, $reciever2);
        }
        $mservice->sendEmail($mailer, $recievers, $emailTemplate->getName(), $message);

        $this->addFlash("success", "Project plan $status successfully.");
        return $this->redirectToRoute('project_show', ["id" => $project->getId()]);
    }

    #[Route('/{id}/implementation', name: 'project_start_implementation', methods: ['POST'])]
    public function startImplementation(Project $project, ProjectRepository $projectRepository, Request $request, MailerInterface $mailer, MailerService $mservice, EmailTemplateRepository $emailTemplateRepository, ProjectAccessService $projectAccessService)
    {
        $projectAccessService->canUserAccessProject($this->getUser(), $project);
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

    #[Route('/{id}/requestModification', name: 'plan_modification_request', methods: ['POST'])]
    public function requestModification(Project $project, ProjectRepository $projectRepository, Request $request, MailerInterface $mailer, MailerService $mservice, EmailTemplateRepository $emailTemplateRepository, ProjectAccessService $projectAccessService)
    {
        $projectAccessService->canUserAccessProject($this->getUser(), $project);
        $comment = $request->request->get('comment');
        $entityManager = $this->getDoctrine()->getManager();
        $planModificationRequest = new PlanModificationRequest();
        $planModificationRequest->setProject($project);
        $planModificationRequest->setComment($comment);
        $planModificationRequest->setCreatedBy($this->getUser());
        $planModificationRequest->setCreatedAt(new \DateTime());
        $planModificationRequest->setStatus(1);
        $entityManager->persist($planModificationRequest);
        $entityManager->flush();
        $emailTemplate = $emailTemplateRepository->findOneBy(['code' => 'plan_modification_request']);
        $message = $emailTemplate->getContent();
        $projectName = $project->getName();
        $message = str_replace('$project', $projectName, $message);
        $message = str_replace('$comment', $comment, $message);
        $recievers = array();
        if($project->getUnit()->getHead()){
            $reciever1 = $project->getUnit()->getHead()->getEmail();
            array_push($recievers, $reciever1);
        }
        if($project->getProjectManager()){
            $reciever2 = $project->getProjectManager()->getEmail();
            array_push($recievers, $reciever2);
        }
        $mservice->sendEmail($mailer, $recievers, $emailTemplate->getName(), $message);

        $this->addFlash("success", "Submitted plan modification request successfully.");
        return $this->redirectToRoute('project_show', ["id" => $project->getId()]);
    }

    #[Route('/{id}/approveRequestMod', name: 'plan_modification_approve', methods: ['POST'])]
    public function approveRequestMod(Project $project, PlanModificationRequestRepository $planModificationRequestRepository, Request $request, MailerInterface $mailer, MailerService $mservice, EmailTemplateRepository $emailTemplateRepository, ProjectAccessService $projectAccessService)
    {
        $projectAccessService->canUserAccessProject($this->getUser(), $project);
        $comment = $request->request->get('approver_comment');
        $req_id = $request->request->get('req_id');
        $status = $request->request->get('status');
        $decision = '';
        if ($status == 2) {
            $decision = 'ACCEPTED';
        } else {
            $decision = 'REJECTED';
        }
        $planModificationRequest = $planModificationRequestRepository->findOneBy(['id' => $req_id]);
        $entityManager = $this->getDoctrine()->getManager();
        $planModificationRequest->setApproverComment($comment);
        $planModificationRequest->setApprovedBy($this->getUser());
        $planModificationRequest->setApprovedAt(new \DateTime());
        $planModificationRequest->setStatus($status);
        $entityManager->persist($planModificationRequest);
        $entityManager->flush();
        if ($status == 2) {
            $project->setStatus(1);
        }
        $emailTemplate = $emailTemplateRepository->findOneBy(['code' => 'plan_modification_status_update']);
        $message = $emailTemplate->getContent();
        $projectName = $project->getName();
        $message = str_replace('$project', $projectName, $message);
        $message = str_replace('$reason', $comment, $message);
        $message = str_replace('$decision', $decision, $message);
        $recievers = array();
        $reciever1 = $planModificationRequest->getCreatedBy()->getEmail();
        array_push($recievers, $reciever1);
        $mservice->sendEmail($mailer, $recievers, $emailTemplate->getName(), $message);

        $this->addFlash("success", "Plan modification request updated successfully.");
        return $this->redirectToRoute('project_show', ["id" => $project->getId()]);
    }

    #[Route('/{id}/comment', name: 'project_plan_comment', methods: ['POST'])]
    public function submitComment(Project $project, Request $request, ProjectAccessService $projectAccessService)
    {
        $projectAccessService->canUserAccessProject($this->getUser(), $project);
        $comment = $request->request->get('comment');
        $entity = $request->request->get('entity');
        $data = $request->request->get('data');
        $data_id = $request->request->get('data_id');
        $entityManager = $this->getDoctrine()->getManager();
        $projectPlanComment = new ProjectPlanComment();
        $projectPlanComment->setProject($project);
        $projectPlanComment->setEntity($entity);
        $projectPlanComment->setData($data);
        $projectPlanComment->setDataId($data_id);
        $projectPlanComment->setComment($comment);
        $projectPlanComment->setCreatedBy($this->getUser());
        $projectPlanComment->setCreatedAt(new \DateTime());
        $entityManager->persist($projectPlanComment);
        $entityManager->flush();

        $this->addFlash("success", "Added plan comment successfully.");
        return $this->redirectToRoute('project_show', ["id" => $project->getId()]);
    }

    #[Route('/{id}/edit', name: 'project_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Project $project, ProjectAccessService $projectAccessService): Response
    {
        $this->denyAccessUnlessGranted('project_edit');
        $projectAccessService->canUserAccessProject($this->getUser(), $project);
        $original = clone $project;
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(), $this->getUser(), $project->getId(), "Project", "UPDATE", $original, $project);
            $entityManager->persist($log);
            $entityManager->flush();
            $this->addFlash("success", "Updated project successfully.");

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
        if ($this->isCsrfTokenValid('delete' . $project->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($project);

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(), $this->getUser(), $project->getId(), "Project", "DELETE", $project);
            $entityManager->persist($log);
            $entityManager->flush();
        }

        $this->addFlash("success", "Deleted project successfully.");

        return $this->redirectToRoute('project_index');
    }
}
