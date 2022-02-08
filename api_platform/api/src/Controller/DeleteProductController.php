<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;

class DeleteProductController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Exception
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     */
    public function __invoke(Request $request): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $dataRequest = $request->getContent();
        $dataRequest = json_decode($dataRequest, true);
        $dataRequest = $dataRequest['data'];
        $response = $this->client->request(
            'POST',
            'http://product:3000/products',
            [
                'body' => [
                    'name' => $dataRequest['name'],
                    'description' => $dataRequest['description'],
                    'image' => $dataRequest['image'],
                    'price' => $dataRequest['price'],
                ]
            ]
        );

        if($response->getStatusCode() === 201) {
            $product = new Product();

            $res=$response->getContent();
            $res = json_decode($res, true);

            $product->setName($res['name']);
            $product->setPrice($res['price']);
            $product->setDescription($res['description']);

            $product->setImageUrl($res['imagesUrl']);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->json(['message' => 'Product created successfully'], 201);
        }else {
            $res=$response->getContent();
            $res = json_decode($res, true);

            return $this->json(['message' => 'Product not created'], 400);
        }
    }
}
