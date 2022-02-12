<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function createPayment(Request $request): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dataRequest = $request->getContent();
        $data = json_decode($dataRequest, true);

        $response = $this->client->request(
            'POST',
            'http://payment:3001/customer',
            [
                "body" => [
                    "email" => $data['email'],
                ]
            ]
        );
        if($response->getStatusCode() === 200) {
            $res=$response->getContent();

            $responseCard = $this->client->request(
                'POST',
                'http://payment:3001/card',
                [
                    "body" => [
                        "customerId" => $res["customerId"],
                        "cardNumber" => $data['cardNumber'],
                        "cardExpMonth" => $data['cardExpMonth'],
                        "cardExpYear" => $data["cardExpYear"],
                        "cvv"=>  $data["cvv"],
                        "cardName"=>  $data["cardName"],
                        "country"=>  $data["country"],
                        "postal_code"=>  $data["postal_code"]
                    ]
                ]
            );
            if($responseCard->getStatusCode() === 200) {
                $resCard=$responseCard->getContent();

                $responsePayment = $this->client->request(
                    'POST',
                    'http://payment:3001/payment',
                    [
                        "body" => [
                            "email" => $data['email'],
                            "customerId" => $res["customerId"],
                            "cardId" => $resCard["cardId"],
                            "amount" => $data['amount'],
                            "currency" => $data['currency'],
                            "description" => $data['description'],
                        ]
                    ]
                );

                if($responsePayment->getStatusCode() === 200) {
                    $resPayment=$responsePayment->getContent();
                    $User = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);

                    if($User){

                        $order = new Order();
                        $order->setOwner($User);
                        $order->setTotalPrice($data['amount']);
                        $order->setStatus("Paid");
                        $order->setAdresse($data['adresse']);


                        $entityManager->persist($order);
                        $entityManager->flush();
                        return $this->json($resPayment);
                    }else{
                        return $this->json(["error"=>"Error when creating payment"]);
                    }

                }
            }
        }
    }
}
