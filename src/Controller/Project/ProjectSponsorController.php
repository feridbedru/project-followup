<?php

namespace App\Controller\Project;

use App\Entity\ProjectSponsor;
use App\Entity\Log;
use App\Form\ProjectSponsorType;
use App\Repository\ProjectRepository;
use App\Repository\ProjectSponsorRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('project/{project}/sponsors')]
class ProjectSponsorController extends AbstractController
{
    #[Route('/', name: 'project_sponsor_index', methods: ['GET', 'POST'])]
    public function index(ProjectSponsorRepository $projectSponsorRepository, ProjectRepository $projectRepository, PaginatorInterface $paginator, Request $request): Response
    { 
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        if($request->request->get('edit')){
            
            $id = $request->request->get('edit');
            $projectSponsor = $projectSponsorRepository->findOneBy(['id'=>$id]);
            $original = clone $projectSponsor;
            $form = $this->createForm(ProjectSponsorType::class, $projectSponsor);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $log = new Log();
                $log =  $log->logEvent($request->getClientIp(), $this->getUser(), $projectSponsor->getId(), "ProjectSponsor", "UPDATE", $original, $projectSponsor);
                $entityManager->persist($log);
                $entityManager->flush();
                $this->addFlash("success","Updated project_sponsor successfully.");

                return $this->redirectToRoute('project_sponsor_index', ["project" => $project->getId()]);
            }

            $queryBuilder = $projectSponsorRepository->findBy(['project' => $project]);
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

            return $this->render('project/sponsor/index.html.twig', [
                'project_sponsors' => $data,
                'form' => $form->createView(),
                'edit' => $id,
                'project' => $project
            ]);
        }

        
        $projectSponsor = new ProjectSponsor();
        $form = $this->createForm(ProjectSponsorType::class, $projectSponsor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $projectSponsor->setCreatedAt(new \DateTime());
            $projectSponsor->setCreatedBy($this->getUser());
            $projectSponsor->setProject($project);
            $entityManager->persist($projectSponsor);
            $entityManager->flush();

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(), $this->getUser(), $projectSponsor->getId(), "ProjectSponsor", "CREATE", $projectSponsor);
            $entityManager->persist($log);
            $entityManager->flush();
            $this->addFlash("success","Registered project_sponsor successfully.");

            return $this->redirectToRoute('project_sponsor_index', ["project" => $project->getId()]);
        }

        $queryBuilder = $projectSponsorRepository->findBy(['project' => $project]);
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

        return $this->render('project/sponsor/index.html.twig', [
            'project_sponsors' => $data,
            'form' => $form->createView(),
            'edit' => false,
            'project' => $project
        ]);

    }

    #[Route('/{id}', name: 'project_sponsor_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectSponsor $projectSponsor, ProjectRepository $projectRepository): Response
    {
        $this->denyAccessUnlessGranted('project_sponsor_delete');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        if ($this->isCsrfTokenValid('delete'.$projectSponsor->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($projectSponsor);

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(), $this->getUser(), $projectSponsor->getId(), "ProjectSponsor", "DELETE", $projectSponsor);
            $entityManager->persist($log);
            $entityManager->flush();
        }
        $this->addFlash("success","Deleted project_sponsor successfully.");

        return $this->redirectToRoute('project_sponsor_index', ["project" => $project->getId()]);
    }
}
