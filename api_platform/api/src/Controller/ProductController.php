<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
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
        $product = $request->attributes->get('data');

        $response = $this->client->request(
            'POST',
            'http://product:3000/products',
            [
                'body' => [
                    'name' => $product->getName(),
                    'price' => array($product->getPrice()),
                    'description' => $product->getDescription(),
                ]
            ]
        );

        if($response->getStatusCode() !== 201) {
            return $this->json($response->getContent());
        }else{

            $content = $response->toArray();
            $product = new Product();
            $product->setName($content['name']);
            $product->setPrice($content['price']);
            $product->setDescription($content['description']);
            $product->setImageUrl($content['image']);
            $product->setCategory($content['category']);

            return $this->json($product);
        }
    }
}
