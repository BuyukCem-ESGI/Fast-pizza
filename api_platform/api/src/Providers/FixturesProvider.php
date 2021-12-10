<?php

namespace App\Providers;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class FixturesProvider
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * Hash password
     * @param string $plainPassword
     * @return string
     */
    public function hashPassword(string $plainPassword): string
    {
        return $this->hasher->hashPassword(new User(),$plainPassword);
    }

    /**
     * Convert string to integer
     * @param $number
     * @return int
     */
    public function convertStringToInteger($number): int
    {
        return (int)$number;
    }

}
