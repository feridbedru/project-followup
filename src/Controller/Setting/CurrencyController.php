<?php

namespace App\Controller\Setting;

use App\Entity\Currency;
use App\Entity\Log;
use App\Form\CurrencyType;
use App\Repository\CurrencyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/currency')]
class CurrencyController extends AbstractController
{
    #[Route('/', name: 'currency_index', methods: ['GET', 'POST'])]
    public function index(CurrencyRepository $currencyRepository, PaginatorInterface $paginator, Request $request): Response
    {
        if($request->request->get('edit')){
            
            $id = $request->request->get('edit');
            $currency = $currencyRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(CurrencyType::class, $currency);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash("success","Updated currency successfully.");

                return $this->redirectToRoute('currency_index');
            }

            $queryBuilder = $currencyRepository->findCurrency($request->query->get('search'));
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

            return $this->render('setting/currency/index.html.twig', [
                'currencies' => $data,
                'form' => $form->createView(),
                'edit' => $id
            ]);
        }

        
        $currency = new Currency();
        $form = $this->createForm(CurrencyType::class, $currency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($currency);
            $entityManager->flush();
            $this->addFlash("success","Registered currency successfully.");

            return $this->redirectToRoute('currency_index');
        }

        $queryBuilder = $currencyRepository->findCurrency($request->query->get('search'));
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page',1), 10 );

        return $this->render('setting/currency/index.html.twig', [
            'currencies' => $data,
            'form' => $form->createView(),
            'edit' => false
        ]);

    }

    #[Route('/{id}', name: 'currency_delete', methods: ['POST'])]
    public function delete(Request $request, Currency $currency): Response
    {
        $this->denyAccessUnlessGranted('currency_delete');
        if ($this->isCsrfTokenValid('delete'.$currency->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($currency);
            $entityManager->flush();
        }
        $this->addFlash("success","Deleted currency successfully.");

        return $this->redirectToRoute('currency_index');
    }
}
