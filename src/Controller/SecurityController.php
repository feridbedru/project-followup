<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    #[Route('/gen', name: 'generate')]
    public function generate()
    {
        $entity_name = 'Report';
        $entity_small = preg_replace('/\s+/', '_', strtolower($entity_name));
        echo "INSERT INTO `permission`(`name`, `code`, `description`) VALUES ('".$entity_name." Create','".$entity_small."_create', 'Allows users to create ".$entity_name."');";
        echo "INSERT INTO `permission`(`name`, `code`, `description`) VALUES ('".$entity_name." Edit','".$entity_small."_edit', 'Allows users to edit ".$entity_name."');";
        echo "INSERT INTO `permission`(`name`, `code`, `description`) VALUES ('".$entity_name." Delete','".$entity_small."_delete', 'Allows users to delete ".$entity_name."');";
        echo "INSERT INTO `permission`(`name`, `code`, `description`) VALUES ('".$entity_name." Show','".$entity_small."_show', 'Allows users to view ".$entity_name."');";
        echo "INSERT INTO `permission`(`name`, `code`, `description`) VALUES ('".$entity_name." List','".$entity_small."_index', 'Allows users to list ".$entity_name."');";
        // return $entity_spaceless;
    }
}
