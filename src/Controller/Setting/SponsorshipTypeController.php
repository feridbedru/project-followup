<?php

namespace App\Controller\Setting;

use App\Entity\SponsorshipType;
use App\Entity\Log;
use App\Form\SponsorshipTypeType;
use App\Repository\SponsorshipTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/sponsorshiptype')]
class SponsorshipTypeController extends AbstractController
{
    #[Route('/', name: 'sponsorship_type_index', methods: ['GET', 'POST'])]
    public function index(SponsorshipTypeRepository $sponsorshipTypeRepository, PaginatorInterface $paginator, Request $request): Response
    {
        if($request->request->get('edit')){
            
            $id = $request->request->get('edit');
            $sponsorshipType = $sponsorshipTypeRepository->findOneBy(['id'=>$id]);
            $original = clone $sponsorshipType;
            $form = $this->createForm(SponsorshipTypeType::class, $sponsorshipType);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $log = new Log();
                $log =  $log->logEvent($request->getClientIp(),$this->getUser(),$sponsorshipType->getId(),"SponsorshipType","UPDATE",$original, $sponsorshipType);
                $entityManager->persist($log);
                $entityManager->flush();
                $this->addFlash("success","Updated sponsorship type successfully.");

                return $this->redirectToRoute('sponsorship_type_index');
            }

            $queryBuilder = $sponsorshipTypeRepository->findSponsorshipType($request->query->get('search'));
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

            return $this->render('setting/sponsorship_type/index.html.twig', [
                'sponsorship_types' => $data,
                'form' => $form->createView(),
                'edit' => $id
            ]);
        }

        
        $sponsorshipType = new SponsorshipType();
        $form = $this->createForm(SponsorshipTypeType::class, $sponsorshipType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sponsorshipType);
            $entityManager->flush();

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(),$this->getUser(),$sponsorshipType->getId(),"SponsorshipType","CREATE", $sponsorshipType);
            $entityManager->persist($log);
            $entityManager->flush();
            $this->addFlash("success","Registered sponsorship type successfully.");

            return $this->redirectToRoute('sponsorship_type_index');
        }

        $queryBuilder = $sponsorshipTypeRepository->findSponsorshipType($request->query->get('search'));
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

        return $this->render('setting/sponsorship_type/index.html.twig', [
            'sponsorship_types' => $data,
            'form' => $form->createView(),
            'edit' => false
        ]);

    }

    #[Route('/{id}', name: 'sponsorship_type_delete', methods: ['POST'])]
    public function delete(Request $request, SponsorshipType $sponsorshipType): Response
    {
        $this->denyAccessUnlessGranted('sponsorship_type_delete');
        if ($this->isCsrfTokenValid('delete'.$sponsorshipType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sponsorshipType);
            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(),$this->getUser(),$sponsorshipType->getId(),"SponsorshipType","DELETE", $sponsorshipType);
            $entityManager->persist($log);
            $entityManager->flush();
        }
        $this->addFlash("success","Deleted sponsorship type successfully.");

        return $this->redirectToRoute('sponsorship_type_index');
    }
}
