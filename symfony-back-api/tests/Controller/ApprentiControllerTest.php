<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Generator;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ApprentiControllerTest extends ApiTestCase
{
    private static string $BASE_ROUTE = "/api/apprenti";

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testGetApprentis(): void
    {
        $response = static::createClient()->request('GET', self::$BASE_ROUTE . '/apprenti');

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
     * @covers       \App\Controller\ApprentiController::getInfosApprenti
     * @dataProvider apprentisIdProvider
     */
    public function testGetInfosApprenti($id): void
    {
        $response = static::createClient()->request('GET', self::$BASE_ROUTE . '/' . $id);

        $resp_array = $response->toArray();

        switch ($id) {
            case 1:
                self::assertEquals('Anneau', $resp_array['utilisateur']['nom']);
                self::assertEquals('Simon', $resp_array['utilisateur']['prenom']);
                self::assertEquals('simon.anneau@reseau.eseo.fr', $resp_array['utilisateur']['email']);
                self::assertEquals(true, $resp_array['utilisateur']['actif']);
                break;

            case 30:
                self::assertJsonContains(["ERROR" => "ERREUR : Impossible de récupérer les informations personnelles"]);
                break;
        }
    }

    public function apprentisIdProvider(): Generator
    {
        yield [1];
        yield [30];
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @covers \App\Controller\ApprentiController::createApprenti
     */
    public function testCreateApprentiSuccess(): void
    {
        $response = static::createClient()->request('POST', self::$BASE_ROUTE . '/create', ['json' => [
            'nom' => 'Guiheu',
            'prenom' => 'Jade',
            'motDePasse' => 'jg',
            'tel' => '0123456789',
            'adresse' => '1 rue de la rue',
            'email' => 'a.b@reseau.eseo.fr',
            'actif' => true,
            'promotion' => '1'
        ]])->toArray();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertEquals('Guiheu', $response['utilisateur']['nom']);
        self::assertEquals('Jade', $response['utilisateur']['prenom']);
        self::assertEquals('0123456789', $response['utilisateur']['telephone']);
        self::assertEquals('1 rue de la rue', $response['utilisateur']['adresse']);
        self::assertEquals('a.b@reseau.eseo.fr', $response['utilisateur']['email']);
        self::assertEquals(true, $response['utilisateur']['actif']);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @covers \App\Controller\ApprentiController::createApprenti
     */
    public function testCreateApprentiFail(): void
    {
        $response = static::createClient()->request('POST', self::$BASE_ROUTE . '/create', ['json' => [
            'nom' => 123,
            'prenom' => 456,
            'motDePasse' => 'jg',
            'tel' => 1234567890,
            'adresse' => '1 rue de la rue',
            'email' => 'a.b@reseau.eseo.fr',
            'actif' => true,
            'promotion' => 1
        ]]);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertJsonContains([]);
    }
}
