<?php

namespace App\Controller;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class MyclassTest extends AbstractController
{
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    #[Route(
        name: 'delete_post',
        path: '/products/{id}',
        methods: ['DELETE'],
        defaults: [
            '_api_item_operation_name' => 'delete',
        ],
        requirements: [
            'id' => '\d+',
        ],
    )]
    public function deleteProduct(Request $request,$id): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if(empty($product->getProductMicroserviceId())) {
            throw $this->createNotFoundException(
                'No product found for id ' . $product->getProductMicroserviceId()
            );
        }

        $response = $this->client->request(
            'DELETE',
            'http://product:3000/products/'.$product->getProductMicroserviceId()
        );

        if($response->getStatusCode() === 201 || $response->getStatusCode() === 200) {
            $entityManager = $this->getDoctrine()->getManager();
            if (!$product) {
                throw $this->createNotFoundException(
                    'No product found for id ' . $id
                );
            }
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
