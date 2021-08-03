<?php

namespace App\Controller\Setting;

use App\Entity\OrganizationUnit;
use App\Entity\Log;
use App\Form\OrganizationUnitType;
use App\Repository\OrganizationUnitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/organizationunit')]
class OrganizationUnitController extends AbstractController
{
    #[Route('/', name: 'organization_unit_index', methods: ['GET', 'POST'])]
    public function index(OrganizationUnitRepository $organizationUnitRepository, PaginatorInterface $paginator, Request $request): Response
    {
        if($request->request->get('edit')){
            
            $id = $request->request->get('edit');
            $organizationUnit = $organizationUnitRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(OrganizationUnitType::class, $organizationUnit);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash("success","Updated organization unit successfully.");

                return $this->redirectToRoute('organization_unit_index');
            }

            $queryBuilder = $organizationUnitRepository->findOrganizationUnit($request->query->get('search'));
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

            return $this->render('setting/organization_unit/index.html.twig', [
                'organization_units' => $data,
                'form' => $form->createView(),
                'edit' => $id
            ]);
        }

        
        $organizationUnit = new OrganizationUnit();
        $form = $this->createForm(OrganizationUnitType::class, $organizationUnit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($organizationUnit);
            $entityManager->flush();
            $this->addFlash("success","Registered organization unit successfully.");

            return $this->redirectToRoute('organization_unit_index');
        }

        $queryBuilder = $organizationUnitRepository->findOrganizationUnit($request->query->get('search'));
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

        return $this->render('setting/organization_unit/index.html.twig', [
            'organization_units' => $data,
            'form' => $form->createView(),
            'edit' => false
        ]);

    }

    #[Route('/{id}', name: 'organization_unit_delete', methods: ['POST'])]
    public function delete(Request $request, OrganizationUnit $organizationUnit): Response
    {
        $this->denyAccessUnlessGranted('organization_unit_delete');
        if ($this->isCsrfTokenValid('delete'.$organizationUnit->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($organizationUnit);
            $entityManager->flush();
        }
        $this->addFlash("success","Deleted organization unit successfully.");

        return $this->redirectToRoute('organization_unit_index');
    }
}
