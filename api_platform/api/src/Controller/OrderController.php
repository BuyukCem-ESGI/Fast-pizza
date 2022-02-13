<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Order;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function __invoke(Request $request): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dataRequest = $request->getContent();

        $data = json_decode($dataRequest, true);
        $response = $this->client->request(
            'POST',
            'http://payment:3001/customer',
            [
                "body" => [
                    "email" => $data['userData']['email'],
                ],
                'headers' => ['authorization' => $_ENV["PAYMENT_MICRO_SERVICE"]]
            ]
        );

        if($response->getStatusCode() === 200) {

            $res=$response->getContent();
            $decodedRes = json_decode($res, true);

            $responseCard = $this->client->request(
                'POST',
                'http://payment:3001/card',
                [
                    'headers' => [
                        'authorization' =>$_ENV["PAYMENT_MICRO_SERVICE"]
                    ],
                    "body" => [
                        "customerId" => $decodedRes["customerId"],
                        "cardNumber" => $data['card']['cardNumber'],
                        "cardExpMonth" => $data['card']['cardExpMonth'],
                        "cardExpYear" => $data['card']["cardExpYear"],
                        "cvv"=>  $data['card']["cardCvv"],
                        "cardName"=>  $data['userData']["lastName"],
                        "country"=>   $data['adress']["country"],
                        "postal_code"=>  $data['adress']["postal_code"]
                    ]
                ]
            );
            if($responseCard->getStatusCode() === 200) {
                $resCard=$responseCard->getContent();
                $decodedResCard = json_decode($resCard, true);

                $responsePayment = $this->client->request(
                    'POST',
                    'http://payment:3001/intents/'.$decodedRes["customerId"],
                    [
                        'headers' => [
                            'authorization' => 'application/json',
                            'authorization' =>$_ENV["PAYMENT_MICRO_SERVICE"]
                        ],
                        "body" => [
                            "email" => $data['userData']['email'],
                            "customerId" => $decodedRes["customerId"],
                            "cardId" => $decodedResCard["cards"],
                            "amount" => $data['total'],
                            "currency" => "eur",
                            "description" => 'payment order',
                        ]
                    ]
                );
                if($responsePayment->getStatusCode() === 200) {
                    $resPayment=$responsePayment->getContent();
                    $decodedResPayment = json_decode($resPayment, true);

                    $User = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['userData']['email']]);

                    if($User && $decodedResPayment["Success"]["paid"] == true) {

                        $address = new Address();

                        $address->setCountry("France");
                        $address->setStreetNumber($data['adress']['street_number']);
                        $address->setCity($data['adress']['locality']);
                        $address->setZipCode($data['adress']["postal_code"]);
                        $address->setStreet($data['adress']["route"]);

                        $entityManager->persist($address);
                        $entityManager->flush();


                        $order = new Order();
                        $order->setOwner($User);
                        $order->setTotalPrice($data['total']);
                        $order->setStatus("En cours de preparation");
                        $order->setDeliverStatus("En cours de livraison");
                        $order->setPaymentId($decodedResPayment["Success"]["id"]);

                        $order->setAddress($address);
                        $address->setOrders($order);
                        $entityManager->persist($order);

                        $entityManager->persist($order);
                        $entityManager->flush();
                        return $this->json(["success" => true, "message" => "Votre commande a bien été validée"]);
                    }else{
                        return $this->json(["error"=>"Error when creating payment"]);
                    }

                }

            }
        }
    }
}
