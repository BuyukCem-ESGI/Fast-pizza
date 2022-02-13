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
    public function getCurrentUser(Request $request,$id): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            return $this->json(['message' => 'Product not found'], 404);
        }

        $response = $this->client->request(
            'DELETE',
            'http://product:3000/products/'.$product->getProductMicroserviceId()
        );

        if($response->getStatusCode() === 201 || $response->getStatusCode() === 200) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();

            return $this->json(['message' => 'Product delete successfully'], 201);
        }else {
            $res=$response->getContent();
            $res = json_decode($res, true);

            return $this->json(['message' => 'Product not created'], 400);
        }
    }
}
