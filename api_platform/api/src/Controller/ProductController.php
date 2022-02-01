<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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
    public function __invoke(Product $data): \Symfony\Component\HttpFoundation\JsonResponse
    {

        #$password = base64_encode("%env(PRODUCT_API_LOGIN)%:%env(PRODUCT_API_PASSWORD)%");
        $response = $this->client->request(
            'POST',
            'http://product:3000/products',
            [
                'body' => [
                    $data
                ]
            ]
        );

        if($response->getStatusCode() !== 200) {
            return $this->json($response);
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
