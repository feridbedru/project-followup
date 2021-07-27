<?php

namespace App\Controller\Setting;

use App\Entity\Sponsor;
use App\Entity\Log;
use App\Form\SponsorType;
use App\Repository\SponsorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/sponsor')]
class SponsorController extends AbstractController
{
    #[Route('/', name: 'sponsor_index', methods: ['GET', 'POST'])]
    public function index(SponsorRepository $sponsorRepository, PaginatorInterface $paginator, Request $request): Response
    {
        if($request->request->get('edit')){
            
            $id = $request->request->get('edit');
            $sponsor = $sponsorRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(SponsorType::class, $sponsor);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash("success","Updated sponsor successfully.");

                return $this->redirectToRoute('sponsor_index');
            }

            $queryBuilder = $sponsorRepository->findSponsor($request->query->get('search'));
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

            return $this->render('setting/sponsor/index.html.twig', [
                'sponsors' => $data,
                'form' => $form->createView(),
                'edit' => $id
            ]);
        }

        
        $sponsor = new Sponsor();
        $form = $this->createForm(SponsorType::class, $sponsor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sponsor);
            $entityManager->flush();
            $this->addFlash("success","Registered sponsor successfully.");

            return $this->redirectToRoute('sponsor_index');
        }

        $queryBuilder = $sponsorRepository->findSponsor($request->query->get('search'));
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

        return $this->render('setting/sponsor/index.html.twig', [
            'sponsors' => $data,
            'form' => $form->createView(),
            'edit' => false
        ]);

    }

    #[Route('/{id}', name: 'sponsor_delete', methods: ['POST'])]
    public function delete(Request $request, Sponsor $sponsor): Response
    {
        $this->denyAccessUnlessGranted('sponsor_delete');
        if ($this->isCsrfTokenValid('delete'.$sponsor->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sponsor);
            $entityManager->flush();
        }
        $this->addFlash("success","Deleted sponsor successfully.");

        return $this->redirectToRoute('sponsor_index');
    }
}
