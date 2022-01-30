<?php
namespace App\Tests;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class CategoryTest extends ApiTestCase{
    public array $categoryOne = [];
    public array $categoryTwo = [];
    public array $categoryThree = [];

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        print_r('Hello');
        $this->categoryOne =[
            'libelle' => 'Boisson',
        ];
        $this->categoryTwo =[
            'libelle' => 'Desserts',
        ];
        $this->categoryThree =[
            'libelle' => 'Sauces',
        ];
        print_r($this);
    }
    public function testLoginUser(): void
    {

        /*
        $response = static::createClient()->request('POST', '/categories', [
            'json' => $this->categoryOne,
            'base_uri' => 'http://localhost:80',
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json',
                'Authorization' => 'Bearer ' . UserTest->
            ]
        ]);
       $this->assertResponseStatusCodeSame(200);
        */


    }
}
