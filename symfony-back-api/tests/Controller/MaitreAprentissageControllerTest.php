<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Generator;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MaitreAprentissageControllerTest extends ApiTestCase
{
    private static String $BASE_ROUTE = "/api/maitre-aprentissage";

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testGetMaitresApprentissage(): void
    {
        $response = static::createClient()->request('GET', self::$BASE_ROUTE.'/all');

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
    public function testCreateMA(): void
    {
        $response = static::createClient()->request('POST', self::$BASE_ROUTE.'/create', ['json' => [
            'entreprise' => 9,
            'nom' => 'Clavreul',
            'prenom' => 'Mickaël',
            'tel' => '0123456789',
            'adresse' => '1 rue de la rue',
            'email' => 'm.c@reseau.eseo.fr',
            'actif' => true
        ]])->toArray();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertEquals('Clavreul', $response['utilisateur']['nom']);
        self::assertEquals('Mickaël', $response['utilisateur']['prenom']);
        self::assertEquals('0123456789', $response['utilisateur']['telephone']);
        self::assertEquals('1 rue de la rue', $response['utilisateur']['adresse']);
        self::assertEquals('m.c@reseau.eseo.fr', $response['utilisateur']['email']);
        self::assertEquals(true, $response['utilisateur']['actif']);
    }

    /**
     * @param $id
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @dataProvider maIdProvider
     */
    public function testGetMaitreApprentissageById($id): void
    {
        $response = static::createClient()->request('GET', self::$BASE_ROUTE.'/'.$id);

        switch ($id) {
            case 2:
                $response = $response->toArray();

                self::assertResponseIsSuccessful();
                self::assertResponseStatusCodeSame(200);

                self::assertNotEmpty($response['utilisateur']);
                self::assertNotEmpty($response['entreprise']);

                self::assertNotEmpty($response['utilisateur']['nom']);
                self::assertIsString($response['utilisateur']['nom']);
                self::assertNotEmpty($response['utilisateur']['prenom']);
                self::assertIsString($response['utilisateur']['prenom']);
                self::assertNotEmpty($response['utilisateur']['motDePasse']);
                self::assertIsString($response['utilisateur']['motDePasse']);
                self::assertNotEmpty($response['utilisateur']['telephone']);
                self::assertIsString($response['utilisateur']['telephone']);
                self::assertNotEmpty($response['utilisateur']['adresse']);
                self::assertIsString($response['utilisateur']['adresse']);
                self::assertNotEmpty($response['utilisateur']['motDePasse']);
                self::assertIsBool($response['utilisateur']['actif']);
                self::assertTrue($response['utilisateur']['actif']);

                self::assertNotEmpty($response['entreprise']['nom']);
                self::assertIsString($response['entreprise']['nom']);
                self::assertNotEmpty($response['entreprise']['adresse']);
                self::assertIsString($response['entreprise']['adresse']);
                self::assertNotEmpty($response['entreprise']['ville']);
                self::assertIsString($response['entreprise']['ville']);
                self::assertNotEmpty($response['entreprise']['codePostal']);
                self::assertIsString($response['entreprise']['codePostal']);
                break;

            case 50:
                self::assertResponseIsSuccessful();
                self::assertResponseStatusCodeSame(200);
                break;
        }
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @dataProvider maIdUserProvider
     */
    public function testGetApprentisMA($id): void
    {
        $response = static::createClient()->request('GET', self::$BASE_ROUTE.'/'.$id.'/apprentis');

        switch ($id) {
            case 18:
                $response = $response->toArray()[0];

                self::assertResponseIsSuccessful();
                self::assertResponseStatusCodeSame(200);

                self::assertNotEmpty($response['utilisateur']);
                self::assertNotEmpty($response['enseignant']);
                self::assertNotEmpty($response['maitreApprentissage']);
                self::assertNotEmpty($response['idPromotion']);

                self::assertIsString($response['utilisateur']['email']);
                self::assertEquals('simon.anneau@reseau.eseo.fr', $response['utilisateur']['email']);
                break;

            case 50:
                self::assertResponseStatusCodeSame(400);
                break;
        }
    }

    public function maIdProvider(): Generator
    {
        yield [2];
        yield [50];
    }

    public function maIdUserProvider(): Generator
    {
        yield [18];
        yield [50];
    }
}
