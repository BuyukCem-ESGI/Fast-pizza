<?php

namespace App\Security\Voter;

use App\Entity\Category;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CategoryVoter extends Voter
{
    //private Security $security ;

    /**
     * @param Security $security
     */
   /* public function __construct(Security $security){
        $this->security = $security ;
    }*/

    /**
     * Vérifiee que la permission (ou le role) existe dans le voter
     * @param $attribute
     * @param $subject
     * @return bool
     */
    protected function supports($attribute, $subject): bool
    {
        $supportsAttribute = in_array($attribute, ['CATEGORY_CREATE', 'CATEGORY_READ', 'CATEGORY_EDIT',
            'CATEGORY_DELETE']);
        $supportsSubject = $subject instanceof Category;

        return $supportsAttribute && $supportsSubject;
    }

    /**
     * @param string $attribute
     * @param $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'POST_EDIT':
                // logic to determine if the user can EDIT
                // return true or false
                break;
            case 'POST_VIEW':
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }
}
