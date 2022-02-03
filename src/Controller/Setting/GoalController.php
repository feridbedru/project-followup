<?php

namespace App\Controller\Setting;

use App\Entity\Goal;
use App\Entity\Log;
use App\Form\GoalType;
use App\Repository\GoalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/goal')]
class GoalController extends AbstractController
{
    #[Route('/', name: 'goal_index', methods: ['GET', 'POST'])]
    public function index(GoalRepository $goalRepository, PaginatorInterface $paginator, Request $request): Response
    {
        if($request->request->get('edit')){
            
            $id = $request->request->get('edit');
            $goal = $goalRepository->findOneBy(['id'=>$id]);
            $original = clone $goal;
            $form = $this->createForm(GoalType::class, $goal);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $log = new Log();
                $log =  $log->logEvent($request->getClientIp(),$this->getUser(),$goal->getId(),"Goal","UPDATE",$original, $goal);
                $entityManager->persist($log);
                $entityManager->flush();
                $this->addFlash("success","Updated goal successfully.");

                return $this->redirectToRoute('goal_index');
            }

            $queryBuilder = $goalRepository->findGoal($request->query->get('search'));
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

            return $this->render('setting/goal/index.html.twig', [
                'goals' => $data,
                'form' => $form->createView(),
                'edit' => $id
            ]);
        }

        
        $goal = new Goal();
        $form = $this->createForm(GoalType::class, $goal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($goal);
            $entityManager->flush();

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(),$this->getUser(),$goal->getId(),"Goal","CREATE", $goal);
            $entityManager->persist($log);
            $entityManager->flush();
            $this->addFlash("success","Registered goal successfully.");

            return $this->redirectToRoute('goal_index');
        }

        $queryBuilder = $goalRepository->findGoal($request->query->get('search'));
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

        return $this->render('setting/goal/index.html.twig', [
            'goals' => $data,
            'form' => $form->createView(),
            'edit' => false
        ]);

    }

    #[Route('/{id}', name: 'goal_delete', methods: ['POST'])]
    public function delete(Request $request, Goal $goal): Response
    {
        $this->denyAccessUnlessGranted('goal_delete');
        if ($this->isCsrfTokenValid('delete'.$goal->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($goal);

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(),$this->getUser(),$goal->getId(),"Goal"," DELETE", $goal);
            $entityManager->persist($log);

            $entityManager->flush();
        }
        $this->addFlash("success","Deleted goal successfully.");

        return $this->redirectToRoute('goal_index');
    }
}
