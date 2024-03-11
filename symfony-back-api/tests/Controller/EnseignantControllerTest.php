<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use function PHPUnit\Framework\assertEquals;

class EnseignantControllerTest extends ApiTestCase
{
    private static String $BASE_ROUTE = "/api/enseignant";

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testGetEnseignants(): void
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
    public function testCreateEnseignantSuccess(): void
    {
        $response = static::createClient()->request('POST', self::$BASE_ROUTE.'/create', ['json' => [
            'nom' => 'Schang',
            'prenom' => 'Daniel',
            'motDePasse' => 'ds',
            'tel' => '0123456789',
            'adresse' => '1 rue de la rue',
            'email' => 'c.d@reseau.eseo.fr',
            'actif' => true,
            'site' => 1
        ]])->toArray();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertEquals('Schang', $response['utilisateur']['nom']);
        self::assertEquals('Daniel', $response['utilisateur']['prenom']);
        self::assertEquals('0123456789', $response['utilisateur']['telephone']);
        self::assertEquals('1 rue de la rue', $response['utilisateur']['adresse']);
        self::assertEquals('c.d@reseau.eseo.fr', $response['utilisateur']['email']);
        self::assertEquals(true, $response['utilisateur']['actif']);
        self::assertEquals(1, $response['enseignant']['centreFormation']['id']);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testCreateEnseignantFail(): void
    {
        $response = static::createClient()->request('POST', self::$BASE_ROUTE.'/create', ['json' => [
            'nom' => 'Schang',
            'prenom' => 'Daniel',
            'motDePasse' => 'ds',
            'tel' => '0123456789',
            'adresse' => '1 rue de la rue',
            'email' => 'c.d@reseau.eseo.fr',
            'actif' => true,
            'site' => 1
        ]])->toArray();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertJsonContains([]);
    }
}
