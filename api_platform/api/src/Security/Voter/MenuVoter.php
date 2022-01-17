<?php

namespace App\Security\Voter;

use App\Entity\Menu;
use Couchbase\Role;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;

class MenuVoter extends Voter
{
    private $security = null;

    /**
     * @param Security $security
     */
    public function __construct(Security $security){
        $this->security = $security;
    }

    /**
     * Vérifie que la permission (ou le role) existe dans le voter
     * @param string $attribute
     * @param $subject
     * @return bool
     */
    protected function supports(string $attribute, $subject): bool
    {
        $supportsAttribute = in_array($attribute, ['MENU_CREATE', 'MENU_READ', 'MENU_EDIT', 'MENU_DELETE']);
        $supportsSubject = $subject instanceof Menu;

        return $supportsAttribute && $supportsSubject;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if($this->security->isGranted('ROLE_ADMIN')) return true;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {

            case 'MENU_CREATE':
                if($this->security->isGranted(["ROLE_ADMIN","ROLE_CUSTOMER"])){
                    return true ;
                }
                break;
            case 'MENU_EDIT':
                // logic to determine if the user can EDIT
                // return true or false

                break;
            case 'MENU_READ':
                // logic to determine if the user can VIEW
                // return true or false
                if($this->security->isGranted(["ROLE_ADMIN","ROLE_VENDOR"])){
                    return true ;
                }
                break;
            case 'MENU_DELETE':
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }
}
