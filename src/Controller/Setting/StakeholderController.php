<?php

namespace App\Controller\Setting;

use App\Entity\Stakeholder;
use App\Entity\Log;
use App\Form\StakeholderType;
use App\Repository\StakeholderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/stakeholder')]
class StakeholderController extends AbstractController
{
    #[Route('/', name: 'stakeholder_index', methods: ['GET', 'POST'])]
    public function index(StakeholderRepository $stakeholderRepository, PaginatorInterface $paginator, Request $request): Response
    {
        if($request->request->get('edit')){
            
            $id = $request->request->get('edit');
            $stakeholder = $stakeholderRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(StakeholderType::class, $stakeholder);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash("success","Updated stakeholder successfully.");

                return $this->redirectToRoute('stakeholder_index');
            }

            $queryBuilder = $stakeholderRepository->findStakeholder($request->query->get('search'));
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

            return $this->render('setting/stakeholder/index.html.twig', [
                'stakeholders' => $data,
                'form' => $form->createView(),
                'edit' => $id
            ]);
        }

        
        $stakeholder = new Stakeholder();
        $form = $this->createForm(StakeholderType::class, $stakeholder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($stakeholder);
            $entityManager->flush();
            $this->addFlash("success","Registered stakeholder successfully.");

            return $this->redirectToRoute('stakeholder_index');
        }

        $queryBuilder = $stakeholderRepository->findStakeholder($request->query->get('search'));
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

        return $this->render('setting/stakeholder/index.html.twig', [
            'stakeholders' => $data,
            'form' => $form->createView(),
            'edit' => false
        ]);

    }

    #[Route('/{id}', name: 'stakeholder_delete', methods: ['POST'])]
    public function delete(Request $request, Stakeholder $stakeholder): Response
    {
        $this->denyAccessUnlessGranted('stakeholder_delete');
        if ($this->isCsrfTokenValid('delete'.$stakeholder->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($stakeholder);
            $entityManager->flush();
        }
        $this->addFlash("success","Deleted stakeholder successfully.");

        return $this->redirectToRoute('stakeholder_index');
    }
}
