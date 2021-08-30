<?php

namespace App\Controller\Project;

use App\Entity\ProjectMembers;
use App\Entity\Log;
use App\Form\ProjectMembersType;
use App\Repository\ProjectRepository;
use App\Repository\ProjectMembersRepository;
use App\Repository\EmailTemplateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use App\Services\MailerService;

#[Route('project/{project}/members')]
class ProjectMembersController extends AbstractController
{
    #[Route('/', name: 'project_members_index', methods: ['GET', 'POST'])]
    public function index(ProjectMembersRepository $projectMembersRepository, ProjectRepository $projectRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        if ($request->request->get('edit')) {

            $id = $request->request->get('edit');
            $projectMember = $projectMembersRepository->findOneBy(['id' => $id]);
            $original = clone $projectMember;
            $form = $this->createForm(ProjectMembersType::class, $projectMember, array('project' => $project->getId()));
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $log = new Log();
                $log =  $log->logEvent($request->getClientIp(), $this->getUser(), $projectMember->getId(), "ProjectMember", "UPDATE", $original, $projectMember);
                $entityManager->persist($log);
                $entityManager->flush();
                $this->addFlash("success", "Updated project members successfully.");

                return $this->redirectToRoute('project_members_index', ["project" => $project->getId()]);
            }

            $queryBuilder = $projectMembersRepository->findBy(['project' => $project]);
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 10);

            return $this->render('project/members/index.html.twig', [
                'project_members' => $data,
                'form' => $form->createView(),
                'edit' => $id,
                'project' => $project,
            ]);
        }


        $projectMember = new ProjectMembers();
        $form = $this->createForm(ProjectMembersType::class, $projectMember, array('project' => $project->getId()));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $projectMember->setIsWorkingOnTask(0);
            $projectMember->setCreatedBy($this->getUser());
            $projectMember->setCreatedAt(new \DateTime());
            $projectMember->setProject($project);
            $entityManager->persist($projectMember);
            $entityManager->flush();

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(), $this->getUser(), $projectMember->getId(), "ProjectMember", "CREATE", $projectMember);
            $entityManager->persist($log);
            $entityManager->flush();
            $this->addFlash("success", "Registered project members successfully.");

            return $this->redirectToRoute('project_members_index', ["project" => $project->getId()]);
        }

        $queryBuilder = $projectMembersRepository->findBy(['project' => $project]);
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 10);

        return $this->render('project/members/index.html.twig', [
            'project_members' => $data,
            'form' => $form->createView(),
            'edit' => false,
            'project' => $project
        ]);
    }

    #[Route('/{id}/status', name: 'member_status', methods: ['POST'])]
    public function action(ProjectMembersRepository $projectMembersRepository, ProjectMembers $projectMember, ProjectRepository $projectRepository, Request $request, MailerInterface $mailer, MailerService $mservice, EmailTemplateRepository $emailTemplateRepository)
    {
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $template = $emailTemplateRepository->findOneBy(['code' => 'add_individual_to_project']);
        $memberId = $request->request->get('memberId');
        $em = $this->getDoctrine()->getManager();
        $projectMember = $em->getRepository(ProjectMembers::class)->find(['id' => $memberId]);
        $projectMember->setStatus($request->request->get('activateMember'));
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash("success", "Updated member status successfully.");
        $user = $projectMember->getUser()->getFullName();
        $myporject = $projectMember->getProject()->getName();
        $role = $projectMember->getRole()->getName();

        $message =  $template->getContent();
        $message = str_replace('$user', $user, $message);
        $message = str_replace('$myproject', $myporject, $message);
        $message = str_replace('$role', $role, $message);
        $roles = array();
        foreach ($template->getProjectStructures() as $structure) {
            array_push($roles, $structure->getId());
        }
        $members = $projectMembersRepository->findBy(['role' => $roles]);
        $recievers = array();
        foreach ($members as $member) {
            $email = $member->getUser()->getEmail();
            array_push($recievers, $email);
        }
        $sent =  $mservice->sendEmail($mailer, $recievers, $template->getName(), $message);
        return $this->redirectToRoute('project_members_index', ["project" => $project->getId()]);
    }

    #[Route('/{id}/delete', name: 'project_members_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectRepository $projectRepository, ProjectMembers $projectMember): Response
    {
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);

        $this->denyAccessUnlessGranted('project_member_delete');
        if ($this->isCsrfTokenValid('delete' . $projectMember->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($projectMember);
            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(), $this->getUser(), $projectMember->getId(), "ProjectMember", "DELETE", $projectMember);
            $entityManager->persist($log);
            $entityManager->flush();
        }
        $this->addFlash("success", "Deleted project members successfully.");

        return $this->redirectToRoute('project_members_index', ["project" => $project->getId()]);
    }
}
