<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;

class ImageProductController extends AbstractController
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
        $file = $request->files->get('file');
        #$password = base64_encode("%env(PRODUCT_API_LOGIN)%:%env(PRODUCT_API_PASSWORD)%");
        dd($product, $file);

        $response = $this->client->request(
            'POST',
            'http://product:3000/products',
            [
                'body' => [
                    $data
                ]
            ]
        );

        if($response->getStatusCode() !== 201) {
            return $this->json($response->getContent());
        }else{
            // find produt
            $product->setImageUrl($content['image']);
            $product->setCategory($content['category']);

            return $this->json($product);
        }
    }
}
