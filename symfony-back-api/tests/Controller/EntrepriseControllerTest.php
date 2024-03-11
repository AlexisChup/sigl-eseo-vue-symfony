<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Generator;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class EntrepriseControllerTest extends ApiTestCase
{
    private static String $BASE_ROUTE = "/api/entreprise";

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testGetAllEntreprises(): void
    {
        $response = static::createClient()->request('GET', self::$BASE_ROUTE.'/entreprises');

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
    public function testCreateEntreprise(): void
    {
        $response = static::createClient()->request('POST', self::$BASE_ROUTE.'/create', [
            'json' => [
                'nom' => 'ESEO SARL',
                'adresse' => '5 rue de la rue',
                'ville' => 'Angers',
                'codePostal' => '49100',
                'siret' => '123456789123456789'
            ]
        ])->toArray();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertEquals('ESEO SARL', $response['nom']);
        self::assertEquals('5 rue de la rue', $response['adresse']);
        self::assertEquals('Angers', $response['ville']);
        self::assertEquals('49100', $response['codePostal']);
        self::assertEquals('123456789123456789', $response['siret']);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testEditEntrepriseById(): void
    {
        $response = static::createClient()->request('PUT', self::$BASE_ROUTE.'/edit/11', [
            'json' => [
                'nom' => 'ESEO SAS',
                'adresse' => '5 rue de lavenue',
                'ville' => 'Paris',
                'codePostal' => '75000',
                'siret' => '987654321987654321'
            ]
        ])->toArray();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertEquals('ESEO SAS', $response['nom']);
        self::assertEquals('5 rue de lavenue', $response['adresse']);
        self::assertEquals('Paris', $response['ville']);
        self::assertEquals('75000', $response['codePostal']);
        self::assertEquals('987654321987654321', $response['siret']);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testEditEntrepriseByIdFail(): void
    {
        $response = static::createClient()->request('PUT', self::$BASE_ROUTE.'/edit/58', [
            'json' => [
                'nom' => 'ESEO SAS',
                'adresse' => '5 rue de lavenue',
                'ville' => 'Paris',
                'codePostal' => '75000',
                'siret' => '987654321987654321'
            ]
        ]);

        self::assertResponseStatusCodeSame(400);
        self::assertResponseHeaderSame('content-type', 'application/json');
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @dataProvider entreprisesIdProvider
     */
    public function testGetEntrepriseByIdSuccess($id): void
    {
        $response = static::createClient()->request('GET', self::$BASE_ROUTE.'/get/'.$id)->toArray();

        switch ($id) {
            case 6:
                // Pas de test sur le contenu car fixtures
                self::assertResponseIsSuccessful();
                self::assertResponseStatusCodeSame(200);
                self::assertResponseHeaderSame('content-type', 'application/json');
                self::assertNotEmpty($response);
                break;

            case 12:
                self::assertJsonContains(["ERROR" => "ERREUR : Impossible de récupérer les informations de l'entreprise"]);
                break;
        }
    }

    public function entreprisesIdProvider(): Generator
    {
        yield [6];
        yield [12];
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testRemoveEntrepriseById(): void
    {
        $response = static::createClient()->request('DELETE', self::$BASE_ROUTE.'/remove/11');

        self::assertNotEmpty($response);
        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(204);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testRemoveEntrepriseByIdFail(): void
    {
        $response = static::createClient()->request('DELETE', self::$BASE_ROUTE.'/remove/40');

        self::assertNotEmpty($response);
        self::assertResponseStatusCodeSame(400);
    }
}
