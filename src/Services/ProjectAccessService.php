<?php

namespace App\Services;

use App\Entity\Project;
use App\Entity\ProjectMembers;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProjectAccessService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function canUserAccessProject($user, Project $project): Response
    {
        $status = 0;
        $user = $user->getId();
        $underProgram = $project->getProgram();
        $entityManager = $this->entityManager;
        $projectMember = $entityManager->getRepository(ProjectMembers::class)->findOneBy(['project' => $project->getId(), 'user' => $user]);

        if ($project->getProjectManager()->getId() == $user) {
            $status = 1;
        } elseif ($underProgram != null) {
            if ($project->getProgram()->getProgramManager() == $user) {
                $status = 1;
            }
        } elseif ($projectMember != null) {
            if ($projectMember->getUser()->getId() == $user) {
                $status = 1;
            }
        } elseif ($this->security->isGranted('can_view_any_project') || $this->security->isGranted('ROLE_ADMIN')) {
            $status = 1;
        } else {
            $status = 0;
        }
        
        if ($status == 0) {
            throw new AccessDeniedException();
        } else {
            return new Response(200);
        }
    }
}
