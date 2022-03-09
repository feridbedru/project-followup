<?php

namespace App\Controller\Project;

use App\Entity\Log;
use App\Entity\ProjectStructure;
use App\Entity\EmailTemplate;
use App\Repository\EmailTemplateRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityRepository;
use App\Repository\ProjectStructureRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use App\Services\ProjectAccessService;

class ProjectEmailController extends AbstractController
{
    #[Route('/project/{project}/email', name: 'project_email_index')]
    public function index(ProjectRepository $projectRepository, PaginatorInterface $paginator, Request $request, ProjectStructureRepository $projectStructureRepository, EmailTemplateRepository $emailTemplateRepository, ProjectAccessService $projectAccessService): Response
    {
        $project = $projectRepository->findOneBy(['id' => $request->attributes->get('project')]);
        if ($request->request->get('edit')) {
            $id = $request->request->get('edit');
            $template = $emailTemplateRepository->findOneBy(['id' => $request->request->get('edit')]);
            $original = clone $template;
            $em = $this->getDoctrine()->getManager();
            $form = $this->createFormBuilder()
                ->add('projectStructure', EntityType::class, [
                    'class' => ProjectStructure::class, 'placeholder' => "Choose role", 'required' => true, 'expanded' => true, 'multiple' => true,
                    'query_builder' => function (EntityRepository $er) use($project){
                        $res = $er->createQueryBuilder('s')
                            ->andWhere('s.project = :project')
                            ->setParameter('project', $project);
                        return $res;
                    }
                ])
                ->add('emailTemplate', EntityType::class, [
                    'class' => EmailTemplate::class, 'placeholder' => "Choose template", 'required' => true,
                    'query_builder' => function (EntityRepository $er) {
                        $res = $er->createQueryBuilder('t')
                            ->andWhere('t.name is not NULL');
                        return $res;
                    }
                ])
                ->getForm();
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $structures = $projectStructureRepository->findAll();
                foreach ($structures as $structure) {
                    $template->removeProjectStructure($structure);
                }
                $template = $emailTemplateRepository->findOneBy(['id' => $request->request->get('form')['emailTemplate']]);
                $structures = $projectStructureRepository->findBy(['id' => $request->request->get('form')['projectStructure']]);
                foreach ($structures as $structure) {
                    $template->addProjectStructure($structure);
                }
                $entityManager = $this->getDoctrine()->getManager();
                $log = new Log();
                $log =  $log->logEvent($request->getClientIp(), $this->getUser(), $template->getId(), "ProjectEmail", "UPDATE", $original, $template);
                $entityManager->persist($log);
                $entityManager->flush();
            }

            $queryBuilder = $emailTemplateRepository->findAll();
            $data = $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 10);
            return $this->render('project/email/index.html.twig', [
                'emails' => $data,
                'project' => $project,
                'form' => $form->createView(),
                'edit' => $id
            ]);
        }

        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('projectStructure', EntityType::class, [
                'class' => ProjectStructure::class, 'placeholder' => "Choose role", 'required' => true, 'expanded' => true, 'multiple' => true,
                'query_builder' => function (EntityRepository $er) use($project){
                    $res = $er->createQueryBuilder('s')
                        ->andWhere('s.project = :project')
                        ->setParameter('project', $project);
                    return $res;
                }
            ])
            ->add('emailTemplate', EntityType::class, [
                'class' => EmailTemplate::class, 'placeholder' => "Choose template", 'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    $res = $er->createQueryBuilder('t')
                        ->andWhere('t.name is not NULL');
                    return $res;
                }
            ])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $template = $emailTemplateRepository->findOneBy(['id' => $request->request->get('form')['emailTemplate']]);
            $structures = $projectStructureRepository->findBy(['id' => $request->request->get('form')['projectStructure']]);
            foreach ($structures as $structure) {
                $template->addProjectStructure($structure);
            }
            $this->getDoctrine()->getManager()->flush();

            $entityManager = $this->getDoctrine()->getManager();
            $log = new Log();
            $log =  $log->logEvent($request->getClientIp(), $this->getUser(), $template->getId(), "ProjectEmail", "CREATE", $template);
            $entityManager->persist($log);
            $entityManager->flush();
        }

        $queryBuilder = $emailTemplateRepository->findAll();
        $data = $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 10);
        return $this->render('project/email/index.html.twig', [
            'emails' => $data,
            'project' => $project,
            'form' => $form->createView(),
            'edit' => false
        ]);
    }
}
