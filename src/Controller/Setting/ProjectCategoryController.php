<?php

namespace App\Controller\Setting;

use App\Entity\ProjectCategory;
use App\Entity\Log;
use App\Form\ProjectCategoryType;
use App\Repository\ProjectCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/projectcategory')]
class ProjectCategoryController extends AbstractController
{
    #[Route('/', name: 'project_category_index', methods: ['GET', 'POST'])]
    public function index(ProjectCategoryRepository $projectCategoryRepository, PaginatorInterface $paginator, Request $request): Response
    {
        if($request->request->get('edit')){
            
            $id = $request->request->get('edit');
            $projectCategory = $projectCategoryRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(ProjectCategoryType::class, $projectCategory);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash("success","Updated project category successfully.");

                return $this->redirectToRoute('project_category_index');
            }

            $queryBuilder = $projectCategoryRepository->findProjectCategory($request->query->get('search'));
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

            return $this->render('setting/project_category/index.html.twig', [
                'project_categories' => $data,
                'form' => $form->createView(),
                'edit' => $id
            ]);
        }

        
        $projectCategory = new ProjectCategory();
        $form = $this->createForm(ProjectCategoryType::class, $projectCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($projectCategory);
            $entityManager->flush();
            $this->addFlash("success","Registered project category successfully.");

            return $this->redirectToRoute('project_category_index');
        }

        $queryBuilder = $projectCategoryRepository->findProjectCategory($request->query->get('search'));
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

        return $this->render('setting/project_category/index.html.twig', [
            'project_categories' => $data,
            'form' => $form->createView(),
            'edit' => false
        ]);

    }

    #[Route('/{id}', name: 'project_category_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectCategory $projectCategory): Response
    {
        $this->denyAccessUnlessGranted('project_category_delete');
        if ($this->isCsrfTokenValid('delete'.$projectCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($projectCategory);
            $entityManager->flush();
        }
        $this->addFlash("success","Deleted project category successfully.");

        return $this->redirectToRoute('project_category_index');
    }
}
