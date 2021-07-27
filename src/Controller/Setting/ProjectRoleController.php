<?php

namespace App\Controller\Setting;

use App\Entity\ProjectRole;
use App\Entity\Log;
use App\Form\ProjectRoleType;
use App\Repository\ProjectRoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/projectrole')]
class ProjectRoleController extends AbstractController
{
    #[Route('/', name: 'project_role_index', methods: ['GET', 'POST'])]
    public function index(ProjectRoleRepository $projectRoleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        if($request->request->get('edit')){
            
            $id = $request->request->get('edit');
            $projectRole = $projectRoleRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(ProjectRoleType::class, $projectRole);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash("success","Updated project role successfully.");

                return $this->redirectToRoute('project_role_index');
            }

            $queryBuilder = $projectRoleRepository->findProjectRole($request->query->get('search'));
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

            return $this->render('setting/project_role/index.html.twig', [
                'project_roles' => $data,
                'form' => $form->createView(),
                'edit' => $id
            ]);
        }

        
        $projectRole = new ProjectRole();
        $form = $this->createForm(ProjectRoleType::class, $projectRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($projectRole);
            $entityManager->flush();
            $this->addFlash("success","Registered project role successfully.");

            return $this->redirectToRoute('project_role_index');
        }

        $queryBuilder = $projectRoleRepository->findProjectRole($request->query->get('search'));
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

        return $this->render('setting/project_role/index.html.twig', [
            'project_roles' => $data,
            'form' => $form->createView(),
            'edit' => false
        ]);

    }

    #[Route('/{id}', name: 'project_role_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectRole $projectRole): Response
    {
        $this->denyAccessUnlessGranted('project_role_delete');
        if ($this->isCsrfTokenValid('delete'.$projectRole->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($projectRole);
            $entityManager->flush();
        }
        $this->addFlash("success","Deleted project role successfully.");

        return $this->redirectToRoute('project_role_index');
    }
}
