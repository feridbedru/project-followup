<?php

namespace App\Controller\Setting;

use App\Entity\ResourceType;
use App\Entity\Log;
use App\Form\ResourceTypeType;
use App\Repository\ResourceTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/resourcetype')]
class ResourceTypeController extends AbstractController
{
    #[Route('/', name: 'resource_type_index', methods: ['GET', 'POST'])]
    public function index(ResourceTypeRepository $resourceTypeRepository, PaginatorInterface $paginator, Request $request): Response
    {
        if($request->request->get('edit')){
            $id = $request->request->get('edit');
            $resourceType = $resourceTypeRepository->findOneBy(['id'=>$id]);
            $original = clone $resourceType;
            $form = $this->createForm(ResourceTypeType::class, $resourceType);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $log = new Log();
                $log =  $log->logEvent($request->getClientIp(),$this->getUser(),$resourceType->getId(),"ResourceType","UPDATE",$original, $resourceType);
                $entityManager->persist($log);

                $entityManager->flush();

                $this->addFlash("success","Updated resource type successfully.");

                return $this->redirectToRoute('resource_type_index');
            }

            $queryBuilder = $resourceTypeRepository->findResourceType($request->query->get('search'));
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

            return $this->render('setting/resource_type/index.html.twig', [
                'resource_types' => $data,
                'form' => $form->createView(),
                'edit' => $id
            ]);
        }
        
        $resourceType = new ResourceType();
        $form = $this->createForm(ResourceTypeType::class, $resourceType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($resourceType);
            $entityManager->flush();

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(),$this->getUser(),$resourceType->getId(),"ResourceType","CREATE",$resourceType);
            $entityManager->persist($log);
            $entityManager->flush();

            $this->addFlash("success","Registered resource type successfully.");

            return $this->redirectToRoute('resource_type_index');
        }

        $queryBuilder = $resourceTypeRepository->findResourceType($request->query->get('search'));
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

        return $this->render('setting/resource_type/index.html.twig', [
            'resource_types' => $data,
            'form' => $form->createView(),
            'edit' => false
        ]);

    }

    #[Route('/{id}', name: 'resource_type_delete', methods: ['POST'])]
    public function delete(Request $request, ResourceType $resourceType): Response
    {
        $this->denyAccessUnlessGranted('resource_type_delete');
        if ($this->isCsrfTokenValid('delete'.$resourceType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($resourceType);

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(),$this->getUser(),$resourceType->getId(),"ResourceType","DELETE",$resourceType);
            $entityManager->persist($log);
            $entityManager->flush();
        }
        $this->addFlash("success","Deleted resource type successfully.");

        return $this->redirectToRoute('resource_type_index');
    }
}
