<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class PatchProductController extends AbstractController
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
    public function __invoke(Request $request,$id): \Symfony\Component\HttpFoundation\JsonResponse
    {

        $dataRequest = $request->getContent();
        $data = json_decode($dataRequest, true);
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            return $this->json(['message' => 'Product not found'], 404);
        }


        $response = $this->client->request(
            'PATCH',
            'http://product:3000/products/'.$product->getProductMicroserviceId(),
            [
                "body" => $data
            ]
        );

        if($response->getStatusCode() === 201 || $response->getStatusCode() === 200) {
            $res=$response->getContent();
            $res = json_decode($res, true);

            $product->setProductMicroserviceId($res['_id']);
            $product->setName($res['name']);
            $product->setPrice($res['price']);
            $product->setDescription($res['description']);

            if(!empty($res['imagesUrl'])) {
                $product->setImageUrl($res['imagesUrl']);
            }
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->json(['message' => 'Product updated successfully'], 201);
        }else {
            return $this->json(['message' => 'Product not updated'], 400);
        }
    }
}
