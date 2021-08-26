<?php

namespace App\Controller\Setting;

use App\Entity\Objective;
use App\Entity\Log;
use App\Form\ObjectiveType;
use App\Repository\GoalRepository;
use App\Repository\ObjectiveRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('goal/{goal}/objective')]
class ObjectiveController extends AbstractController
{
    #[Route('/', name: 'objective_index', methods: ['GET', 'POST'])]
    public function index(ObjectiveRepository $objectiveRepository, PaginatorInterface $paginator, Request $request,GoalRepository $goalRepository): Response
    {
        $goal = $goalRepository->findOneBy(['id' => $request->attributes->get('goal')]);
        if($request->request->get('edit')){
            
            $id = $request->request->get('edit');
            $objective = $objectiveRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(ObjectiveType::class, $objective);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash("success","Updated objective successfully.");

                return $this->redirectToRoute('objective_index', ["goal" => $goal->getId()]);
            }

            $queryBuilder = $objectiveRepository->findBy(['goal' => $goal]);
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

            return $this->render('setting/objective/index.html.twig', [
                'objectives' => $data,
                'form' => $form->createView(),
                'edit' => $id,
                'goal' => $goal
            ]);
        }

        
        $objective = new Objective();
        $form = $this->createForm(ObjectiveType::class, $objective);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $objective->setGoal($goal);
            $entityManager->persist($objective);
            $entityManager->flush();
            $this->addFlash("success","Registered objective successfully.");

            return $this->redirectToRoute('objective_index', ["goal" => $goal->getId()]);
        }

        $queryBuilder = $objectiveRepository->findBy(['goal' => $goal]);
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

        return $this->render('setting/objective/index.html.twig', [
            'objectives' => $data,
            'form' => $form->createView(),
            'edit' => false,
            'goal' => $goal
        ]);

    }

    #[Route('/{id}', name: 'objective_delete', methods: ['POST'])]
    public function delete(Request $request, Objective $objective,GoalRepository $goalRepository): Response
    {
        $this->denyAccessUnlessGranted('objective_delete');
        $goal = $goalRepository->findOneBy(['id' => $request->attributes->get('goal')]);
        if ($this->isCsrfTokenValid('delete'.$objective->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($objective);
            $entityManager->flush();
        }
        $this->addFlash("success","Deleted objective successfully.");

        return $this->redirectToRoute('objective_index', ["goal" => $goal->getId()]);
    }
}
