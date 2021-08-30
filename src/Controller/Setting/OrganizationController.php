<?php

namespace App\Controller\Setting;

use App\Entity\Organization;
use App\Entity\Log;
use App\Form\OrganizationType;
use App\Repository\OrganizationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/organization')]
class OrganizationController extends AbstractController
{
    #[Route('/', name: 'organization_index', methods: ['GET', 'POST'])]
    public function index(OrganizationRepository $organizationRepository, PaginatorInterface $paginator, Request $request): Response
    {
        if($request->request->get('edit')){
            
            $id = $request->request->get('edit');
            $organization = $organizationRepository->findOneBy(['id'=>$id]);
            $original = clone $organization;
            $form = $this->createForm(OrganizationType::class, $organization);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $log = new Log();
                $log =  $log->logEvent($request->getClientIp(),$this->getUser(),$organization->getId(),"Organization","UPDATE",$original, $organization);
                $entityManager->persist($log);
                $entityManager->flush();
                
                $this->addFlash("success","Updated organization successfully.");

                return $this->redirectToRoute('organization_index');
            }

            $queryBuilder = $organizationRepository->findOrganization($request->query->get('search'));
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

            return $this->render('setting/organization/index.html.twig', [
                'organizations' => $data,
                'form' => $form->createView(),
                'edit' => $id
            ]);
        }

        
        $organization = new Organization();
        $form = $this->createForm(OrganizationType::class, $organization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $uploadedFile = $form['logo']->getData();
            if($uploadedFile){
                $location = $this->getParameter('kernel.project_dir') . '/public/upload/logo';
                $newFilename = uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
                $uploadedFile->move($location, $newFilename);
                $organization->setLogo($newFilename);
            }
            $entityManager->persist($organization);
            $entityManager->flush();

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(),$this->getUser(),$organization->getId(),"Organization","CREATE", $organization);
            $entityManager->persist($log);
            $entityManager->flush();

            $this->addFlash("success","Registered organization successfully.");

            return $this->redirectToRoute('organization_index');
        }

        $queryBuilder = $organizationRepository->findOrganization($request->query->get('search'));
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

        return $this->render('setting/organization/index.html.twig', [
            'organizations' => $data,
            'form' => $form->createView(),
            'edit' => false
        ]);

    }

    #[Route('/{id}', name: 'organization_delete', methods: ['POST'])]
    public function delete(Request $request, Organization $organization): Response
    {
        $this->denyAccessUnlessGranted('organization_delete');
        if ($this->isCsrfTokenValid('delete'.$organization->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($organization);

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(),$this->getUser(),$organization->getId(),"Organization","DELETE", $organization);
            $entityManager->persist($log);

            $entityManager->flush();
        }
        $this->addFlash("success","Deleted organization successfully.");

        return $this->redirectToRoute('organization_index');
    }
}
