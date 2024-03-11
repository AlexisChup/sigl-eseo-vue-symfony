<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CoordinateurControllerTest extends ApiTestCase
{
    private static String $BASE_ROUTE = "/api/coordinateur";

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testGetCoordinateurs(): void
    {
        $response = static::createClient()->request('GET', self::$BASE_ROUTE.'/coordinateurs');

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertNotEmpty($response->toArray());
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testCreateCoordinateur(): void
    {
        $response = static::createClient()->request('POST', self::$BASE_ROUTE.'/create', [
            'json' => [
                'entreprise' => 9,
                'nom' => 'Chollet',
                'prenom' => 'Coraline',
                'tel' => '0123456789',
                'adresse' => '1 rue de la rue',
                'email' => 'c.c@reseau.eseo.fr',
                'actif' => true
            ]
        ])->toArray();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertEquals('Chollet', $response['utilisateur']['nom']);
        self::assertEquals('Coraline', $response['utilisateur']['prenom']);
        self::assertEquals('0123456789', $response['utilisateur']['telephone']);
        self::assertEquals('1 rue de la rue', $response['utilisateur']['adresse']);
        self::assertEquals('c.c@reseau.eseo.fr', $response['utilisateur']['email']);
        self::assertEquals(true, $response['utilisateur']['actif']);
    }
}
