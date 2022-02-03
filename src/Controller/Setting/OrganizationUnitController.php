<?php

namespace App\Controller\Setting;

use App\Entity\OrganizationUnit;
use App\Entity\Log;
use App\Form\OrganizationUnitType;
use App\Repository\OrganizationUnitRepository;
use App\Repository\OrganizationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('organization/{organization}/unit')]
class OrganizationUnitController extends AbstractController
{
    #[Route('/', name: 'organization_unit_index', methods: ['GET', 'POST'])]
    public function index(OrganizationUnitRepository $organizationUnitRepository, PaginatorInterface $paginator, Request $request, OrganizationRepository $organizationRepository): Response
    {
        $organization = $organizationRepository->findOneBy(['id' => $request->attributes->get('organization')]);
        if($request->request->get('edit')){
            
            $id = $request->request->get('edit');
            $organizationUnit = $organizationUnitRepository->findOneBy(['id'=>$id]);
            $original = clone $organizationUnit;
            $form = $this->createForm(OrganizationUnitType::class, $organizationUnit);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $log = new Log();
                $log =  $log->logEvent($request->getClientIp(),$this->getUser(),$organizationUnit->getId(),"OrganizationUnit","UPDATE",$original, $organizationUnit);
                $entityManager->persist($log);
                $entityManager->flush();

                $this->addFlash("success","Updated organization unit successfully.");

                return $this->redirectToRoute('organization_unit_index', ["organization" => $organization->getId()]);
            }

            $queryBuilder = $organizationUnitRepository->findBy(['organization' => $organization]);
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

            return $this->render('setting/organization_unit/index.html.twig', [
                'organization_units' => $data,
                'form' => $form->createView(),
                'edit' => $id,
                'organization' => $organization
            ]);
        }

        
        $organizationUnit = new OrganizationUnit();
        $form = $this->createForm(OrganizationUnitType::class, $organizationUnit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $organizationUnit->setOrganization($organization);
            $entityManager->persist($organizationUnit);
            $entityManager->flush();

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(),$this->getUser(),$organizationUnit->getId(),"OrganizationUnit","CREATE", $organizationUnit);
            $entityManager->persist($log);
            $entityManager->flush();

            $this->addFlash("success","Registered organization unit successfully.");

            return $this->redirectToRoute('organization_unit_index', ["organization" => $organization->getId()]);
        }

        $queryBuilder = $organizationUnitRepository->findBy(['organization' => $organization]);
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

        return $this->render('setting/organization_unit/index.html.twig', [
            'organization_units' => $data,
            'form' => $form->createView(),
            'edit' => false,
            'organization' => $organization
        ]);

    }

    #[Route('/{id}', name: 'organization_unit_delete', methods: ['POST'])]
    public function delete(Request $request, OrganizationUnit $organizationUnit, OrganizationRepository $organizationRepository): Response
    {
        $this->denyAccessUnlessGranted('organization_unit_delete');
        $organization = $organizationRepository->findOneBy(['id' => $request->attributes->get('organization')]);
        if ($this->isCsrfTokenValid('delete'.$organizationUnit->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($organizationUnit);

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(),$this->getUser(),$organizationUnit->getId(),"OrganizationUnit","DELETE", $organizationUnit);
            $entityManager->persist($log);
            $entityManager->flush();
        }
        $this->addFlash("success","Deleted organization unit successfully.");

        return $this->redirectToRoute('organization_unit_index', ["organization" => $organization->getId()]);
    }
}
