<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

class PermissionVoter extends Voter
{
    private $requestStack;
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @return mixed
     */
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html

        $session = $this->requestStack->getSession();
        $permission = $session->get("PERMISSION");
        // dd($session);
        if (!$permission)
            $permission = array();
        if ($attribute == "pat_") return true;
        return in_array($attribute, $permission);
    }

    /**
     * @return boolean
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $session = $this->requestStack->getSession();
        $permission = $session->get("PERMISSION");
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'pat_':
                $matches  = preg_grep('/^pat_(\w+)/i', $permission);
                if ($matches)
                    return true;

                else return false;

                // logic to determine if the user can EDIT
                // return true or false
                break;
            case 'POST_VIEW':
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        if (!$permission)
            $permission = array();

        return in_array($attribute, $permission);

        return false;
    }
}
