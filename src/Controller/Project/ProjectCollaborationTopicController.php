<?php

namespace App\Controller\Project;

use App\Entity\ProjectCollaborationTopic;
use App\Entity\Log;
use App\Form\ProjectCollaborationTopicType;
use App\Repository\ProjectRepository;
use App\Repository\ProjectCollaborationTopicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/project/{project}/topic')]
class ProjectCollaborationTopicController extends AbstractController
{
    #[Route('/', name: 'project_collaboration_topic_index', methods: ['GET', 'POST'])]
    public function index(ProjectCollaborationTopicRepository $projectCollaborationTopicRepository, ProjectRepository $projectRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        if($request->request->get('edit')){
            
            $id = $request->request->get('edit');
            $projectCollaborationTopic = $projectCollaborationTopicRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(ProjectCollaborationTopicType::class, $projectCollaborationTopic);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash("success","Updated project collaboration topic successfully.");

                return $this->redirectToRoute('project_collaboration_topic_index', ["project" => $project->getId()]);
            }

            $queryBuilder = $projectCollaborationTopicRepository->findBy(['project' => $project]);
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

            return $this->render('project/collaboration_topic/index.html.twig', [
                'project_collaboration_topics' => $data,
                'form' => $form->createView(),
                'edit' => $id,
                'project' => $project
            ]);
        }

        
        $projectCollaborationTopic = new ProjectCollaborationTopic();
        $form = $this->createForm(ProjectCollaborationTopicType::class, $projectCollaborationTopic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $projectCollaborationTopic->setProject($project);
            $projectCollaborationTopic->setCreatedAt(new \DateTime());
            $projectCollaborationTopic->setCreatedBy($this->getUser());
            $entityManager->persist($projectCollaborationTopic);
            $entityManager->flush();
            $this->addFlash("success","Registered project collaboration topic successfully.");

            return $this->redirectToRoute('project_collaboration_topic_index', ["project" => $project->getId()]);
        }

        $queryBuilder = $projectCollaborationTopicRepository->findBy(['project' => $project]);
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

        return $this->render('project/collaboration_topic/index.html.twig', [
            'project_collaboration_topics' => $data,
            'form' => $form->createView(),
            'edit' => false,
            'project' => $project
        ]);

    }

    #[Route('/{id}', name: 'project_collaboration_topic_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectCollaborationTopic $projectCollaborationTopic, ProjectRepository $projectRepository): Response
    {
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $this->denyAccessUnlessGranted('project_collaboration_topic_delete');
        if ($this->isCsrfTokenValid('delete'.$projectCollaborationTopic->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($projectCollaborationTopic);
            $entityManager->flush();
        }
        $this->addFlash("success","Deleted project collaboration topic successfully.");

        return $this->redirectToRoute('project_collaboration_topic_index', ["project" => $project->getId()]);
    }
}
