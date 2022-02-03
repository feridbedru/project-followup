<?php

namespace App\Controller;

use App\Entity\Report;
use App\Entity\Log;
use App\Form\ReportType;
use App\Repository\ReportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/report')]
class ReportController extends AbstractController
{
    #[Route('/', name: 'report_index', methods: ['GET'])]
    public function index(ReportRepository $reportRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $this->denyAccessUnlessGranted('report_index');
        $reports = $paginator->paginate($reportRepository->findAll(), $request->query->getInt('page', 1), 10);
        return $this->render('report/index.html.twig', [
            'reports' => $reports,
        ]);
    }

    #[Route('/new', name: 'report_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('report_create');
        $report = new Report();
        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $report->setCreatedAt(new \DateTime());
            $report->setCreatedBy($this->getUser());
            $entityManager->persist($report);
            $entityManager->flush();

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(),$this->getUser(),$report->getId(),"Report","CREATE", $report);
            $entityManager->persist($log);
            $entityManager->flush();

            $this->addFlash("success","created report successfully.");

            return $this->redirectToRoute('report_index');
        }

        return $this->render('report/new.html.twig', [
            'report' => $report,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'report_show', methods: ['GET'])]
    public function show(Report $report): Response
    {
        $this->denyAccessUnlessGranted('report_show');
        return $this->render('report/show.html.twig', [
            'report' => $report,
        ]);
    }

    #[Route('/{id}/edit', name: 'report_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Report $report): Response
    {
        $this->denyAccessUnlessGranted('report_edit');
        $original = clone $report;
        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(),$this->getUser(),$report->getId(),"Report","UPDATE",$original, $report);
            $entityManager->persist($log);
            $entityManager->flush();
            $this->addFlash("success","Updated report successfully.");

            return $this->redirectToRoute('report_index');
        }

        return $this->render('report/edit.html.twig', [
            'report' => $report,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'report_delete', methods: ['POST'])]
    public function delete(Request $request, Report $report): Response
    {
        $this->denyAccessUnlessGranted('report_delete');
        if ($this->isCsrfTokenValid('delete'.$report->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($report);

            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(),$this->getUser(),$report->getId(),"Report","DELETE", $report);
            $entityManager->persist($log);

            $entityManager->flush();
        }

        $this->addFlash("success","Deleted report successfully.");

        return $this->redirectToRoute('report_index');
    }
}
