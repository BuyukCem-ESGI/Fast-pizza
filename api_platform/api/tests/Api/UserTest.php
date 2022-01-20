<?php

namespace App\Tests;

use APP\Entity\User;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserTest extends ApiTestCase
{
    public array $payload = [];
    public String $token;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->payload =[
            'email' => 'cembuyuk7@gmail.com',
            'roles' => ['ROLE_ADMIN'],
            'password' => 'OZPZ3R4G',
            'firstname' => 'Test',
            'lastname' => 'Test',
            'phoneNumber' => '1234567890'
        ];
    }
    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testCreateUser(): void
    {
        self::bootKernel();
        $response = static::createClient()->request('POST', '/users', [
            'json' => $this->payload,
            'base_uri' => 'http://localhost:80',
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json',
            ],
        ]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            'firstname' => $this->payload['firstname'],
            'lastname' => $this->payload['lastname']
        ]);
    }
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testCreateUserWithSameEmail ():void
    {
        $response = static::createClient()->request('POST', '/users', [
            'json' => $this->payload,
            'base_uri' => 'http://localhost:80',
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'email: L\'email existe déjà'
        ]);

        #$this->assertSame('foo', array_pop($stack));
    }
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     */
    public function testLoginUser(): void
    {
        $response = static::createClient()->request('POST', '/authentication_token', [
            'json' => $this->payload,
            'base_uri' => 'http://localhost:80',
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json',
            ]
        ]);
        $responseJson = $response->toArray();
        $this->assertNotNull($responseJson['token']);
        $this->assertResponseStatusCodeSame(200);
        $this->token = $responseJson['token'];
    }
    public function testPostCategoryWthNoAuth(): void
    {
        $category = [
            "libelle"=> "Pizza",
        ];
        $response = static::createClient()->request('POST', '/categories', [
            'json' => $category,
            'base_uri' => 'http://localhost:80',
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json'
            ]
        ]);
        $this->assertResponseStatusCodeSame(401);
        $this->assertJsonContains([
            'message' => 'JWT Token not found',
            'code' => 401
        ]);
    }
}
