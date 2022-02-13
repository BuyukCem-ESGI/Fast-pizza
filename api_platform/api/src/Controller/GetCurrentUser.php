<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;

class GetCurrentUser extends AbstractController
{

    private $security;

    public function __construct(HttpClientInterface $client, Security $security)
    {
        $this->security = $security;
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Exception
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     */
    public function getCurrentUser(Request $request): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $user = $this->security->getUser();
        if ($user) {
            return $this->json($user);
        } else {
            return $this->json(['error' => 'User not found'], 404);
        }
    }
}
