<?php

namespace App\Controller\User;

use App\Entity\Permission;
use App\Entity\Log;
use App\Form\PermissionType;
use App\Repository\PermissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/permission')]
class PermissionController extends AbstractController
{
    #[Route('/', name: 'permission_index', methods: ['GET', 'POST'])]
    public function index(PermissionRepository $permissionRepository, Request $request, PaginatorInterface $paginator): Response
    {

        if ($request->request->get('edit')) {
            $id = $request->request->get('edit');
            $permission = $permissionRepository->findOneBy(['id' => $id]);
            $original = clone $permission;
            $form = $this->createForm(PermissionType::class, $permission);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $log = new Log();
                $log =  $log->logEvent($request->getClientIp(), $this->getUser(), $permission->getId(), "Permission", "UPDATE", $original, $permission);
                $entityManager->persist($log);
                $entityManager->flush();

                return $this->redirectToRoute('permission_index');
            }

            $queryBuilder = $permissionRepository->findPermission($request->query->get('search'));
            $data = $paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page', 1),
                18
            );
            return $this->render('user/permission/index.html.twig', [
                'permissions' => $data,
                'form' => $form->createView(),
                'edit' => $id
            ]);
        }
        $permission = new Permission();
        $form = $this->createForm(PermissionType::class, $permission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($permission);
            $entityManager->flush();

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(), $this->getUser(), $permission->getId(), "Permission", "CREATE", $permission);
            $entityManager->persist($log);
            $entityManager->flush();

            return $this->redirectToRoute('permission_index');
        }

        $queryBuilder = $permissionRepository->findPermission($request->query->get('search'));
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            18
        );
        return $this->render('user/permission/index.html.twig', [
            'permissions' => $data,
            'form' => $form->createView(),
            'edit' => false
        ]);
    }

    #[Route('/{id}', name: 'permission_delete', methods: ['POST'])]
    public function delete(Request $request, Permission $permission): Response
    {
        if ($this->isCsrfTokenValid('delete' . $permission->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($permission);

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(), $this->getUser(), $permission->getId(), "Permission", "DELETE", $permission);
            $entityManager->persist($log);

            $entityManager->flush();
        }

        return $this->redirectToRoute('permission_index');
    }
}
