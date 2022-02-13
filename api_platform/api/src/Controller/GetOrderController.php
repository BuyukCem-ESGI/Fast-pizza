<?php

namespace App\Controller;

use App\Entity\Order;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetOrderController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {

        $this->security = $security;
    }

    public function __invoke(Request $request): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->security->getUser();
        if ($user) {
            $order = $entityManager->getRepository(Order::class)->findBy(['owner' => $user->getid()]);
            if(!$order) {
                return $this->json([
                    'message' => 'Order not found'
                ], 404);
            }{
                return $this->json($order, 201);
            }
        }else{
            return $this->json(["error"=>"Error when creating payment"]);
        }
    }
}
