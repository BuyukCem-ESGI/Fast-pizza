<?php

namespace App\Controller;

use App\Entity\Menu;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class deleteMenuController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function __invoke(Request $request, $id): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();

        $menu = $entityManager->getRepository(Menu::class)->find($id);
        if (!$menu->getMenuMicroserviceId()) {
            return $this->json(['message' => 'Menu not found'], 404);
        }

        $response = $this->client->request(
            'DELETE',
            'http://product:3000/menu/' . $menu->getMenuMicroserviceId()
        );
        if ($response->getStatusCode() === 201 || $response->getStatusCode() === 200) {
            $entityManager->remove($menu);
            $entityManager->flush();
            return $this->json(['message' => 'Menu successfully deleted'], 200);
        } else {
            return $this->json(['message' => 'Menu not found'], 404);
        }
    }
}
