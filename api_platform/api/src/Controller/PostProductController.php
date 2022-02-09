<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;

class PostProductController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function __invoke(Request $request): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $dataRequest = $request->getContent();
        $data = json_decode($dataRequest, true);
        $response = $this->client->request(
            'POST',
            'http://product:3000/products',
            [
               "body" => $data
            ]
        );
        if($response->getStatusCode() === 201) {
            $product = new Product();

            $res=$response->getContent();
            $res = json_decode($res, true);
            $product->setProductMicroserviceId($res['_id']);
            $product->setName($res['name']);
            $product->setPrice($res['price']);
            $product->setDescription($res['description']);
            if(!empty($res['imagesUrl'])) {
                $product->setImageUrl($res['imagesUrl']);
            }
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
    /*
        #[Route(
            name: 'delete_product',
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

           #[Route(
               name: 'update_product',
               path: '/products/{id}',
               methods: ['PATCH'],
               defaults: [
                   '_api_item_operation_name' => 'patch',
               ],
               requirements: [
                   'id' => '\d+',
               ],
           )]
           public function putProduct(Request $request,$id): \Symfony\Component\HttpFoundation\JsonResponse
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

                   $entityManager=$this->getDoctrine()->getManager();
                   $entityManager->persist($product);
                   $entityManager->flush();

                   return $this->json(['message' => 'Product updated successfully'], 201);
               }else {
                   return $this->json(['message' => 'Product not updated'], 400);
               }
           }
           */

}
