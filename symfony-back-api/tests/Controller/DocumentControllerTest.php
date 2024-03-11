<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class DocumentControllerTest extends ApiTestCase
{
    private static String $BASE_ROUTE = "/api/document";

    /**
     * @throws TransportExceptionInterface
     */
    public function testPublishDocPedago(): void
    {

        $response = static::createClient()->request('POST', self::$BASE_ROUTE . '/publish',
            ['json' => [
                'type' => 'PEDAGO',
                'user_id' => 1
            ]]);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
    }
}
