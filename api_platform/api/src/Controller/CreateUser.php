<?php
namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class CreateUser extends AbstractController
{
    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function __invoke(User $data): User
    {
        $data->setRoles(['ROLE_USER']);
        $data->setActive(false);
        $data->setCreatedAt(new \DateTime());
        $data->setUpdatedAt(new \DateTime());
        $data->setLastActivity(new \DateTime());

        $password = $this->hasher->hashPassword($data,$data->getPassword());
        $data->setPassword($password);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($data);
        $entityManager->flush();

        return $data;
    }

}
