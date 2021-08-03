<?php

namespace App\Controller\Project;

use App\Entity\ActivityChat;
use App\Entity\Log;
use App\Form\ActivityChatType;
use App\Repository\ActivityChatRepository;
use App\Repository\ProjectRepository;
use App\Repository\ProjectActivityRepository;
use App\Repository\ProjectCollaborationTopicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('project/{project}/topic/{topic}/collaboration')]
class ActivityChatController extends AbstractController
{
    #[Route('/', name: 'activity_chat_index', methods: ['GET', 'POST'])]
    public function index(ActivityChatRepository $activityChatRepository, ProjectCollaborationTopicRepository $projectCollaborationTopicRepository, ProjectRepository $projectRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $topic = $projectCollaborationTopicRepository->findOneBy(['id' => $request->attributes->get('topic')]);
        if($request->request->get('edit')){
            
            $id = $request->request->get('edit');
            $activityChat = $activityChatRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(ActivityChatType::class, $activityChat);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash("success","Updated activity_chat successfully.");

                return $this->redirectToRoute('activity chat_index', ["project" => $project->getId(), "topic" => $topic->getId()]);
            }

            $queryBuilder = $activityChatRepository->findBy(["topic" => $topic]);
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

            return $this->render('project/activity_chat/index.html.twig', [
                'activity_chats' => $data,
                'form' => $form->createView(),
                'edit' => $id,
                'project' => $project,
                'topic' => $topic,
                'user' => $this->getUser(),
            ]);
        }

        
        $activityChat = new ActivityChat();
        $form = $this->createForm(ActivityChatType::class, $activityChat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $activityChat->setTopic($topic);
            $activityChat->setPostedAt(new \DateTime());
            $activityChat->setPostedBy($this->getUser());
            $entityManager->persist($activityChat);
            $entityManager->flush();
            $this->addFlash("success","Registered activity chat successfully.");

            return $this->redirectToRoute('activity_chat_index', ["project" => $project->getId(), "topic" => $topic->getId()]);
        }

        $queryBuilder = $activityChatRepository->findBy(["topic" => $topic]);
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

        return $this->render('project/activity_chat/index.html.twig', [
            'activity_chats' => $data,
            'form' => $form->createView(),
            'edit' => false,
            'project' => $project,
            'topic' => $topic,
            'user' => $this->getUser(),
        ]);

    }

    #[Route('/{id}', name: 'activity_chat_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectCollaborationTopicRepository $projectCollaborationTopicRepository, ProjectRepository $projectRepository, ActivityChat $activityChat): Response
    {
        $this->denyAccessUnlessGranted('activity_chat_delete');
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        $topic = $projectCollaborationTopicRepository->findOneBy(['id' => $request->attributes->get('topic')]);
        if ($this->isCsrfTokenValid('delete'.$activityChat->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($activityChat);
            $entityManager->flush();
        }
        $this->addFlash("success","Deleted activity chat successfully.");

        return $this->redirectToRoute('activity_chat_index', ["project" => $project->getId(), "topic" => $topic->getId()]);
    }
}
