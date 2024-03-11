<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use DateTime;
use Exception;
use Generator;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class EvaluationControllerTest extends ApiTestCase
{
    private static String $BASE_ROUTE = "/api/evaluation";

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testGetAllEvaluations(): void
    {
        $response = static::createClient()->request('GET', self::$BASE_ROUTE.'/evaluations');

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertEmpty($response->toArray());
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testCreateEvaluation(): void
    {
        $response = static::createClient()->request('POST', self::$BASE_ROUTE.'/create', [
            'json' => [
                'user_id' => 10,
                'idEvent' => 1,
                'statut' => 'Déposé',
                'libelle' => 'Evaluation test'
            ]
        ])->toArray();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertNotEmpty($response);
        self::assertEquals('Déposé', $response['statut']);
        self::assertEquals('Evaluation test', $response['libelle']);
        self::assertEquals('Simon Anneau', $response['modifiePar']);
        self::assertEquals(date_format(new DateTime(), 'Y-m-d H:i'), date_format(new DateTime($response['modifieLe']), 'Y-m-d H:i'));
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testGetEvaluationById(): void
    {
        $response = static::createClient()->request('GET', self::$BASE_ROUTE.'/1')->toArray();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertNotEmpty($response);
        self::assertEquals('Déposé', $response['statut']);
        self::assertEquals('Evaluation test', $response['libelle']);
        self::assertEquals('Simon Anneau', $response['modifiePar']);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testEditEvaluationByIdSuccess(): void
    {
        $response = static::createClient()->request('PUT', self::$BASE_ROUTE.'/edit/1', [
            'json' => [
                'user_id' => 11,
                'idEvent' => 2,
                'statut' => 'Validé',
                'libelle' => 'Evaluation test 2'
            ]
        ])->toArray();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertNotEmpty($response);
        self::assertEquals('Validé', $response['statut']);
        self::assertEquals('Evaluation test 2', $response['libelle']);
        self::assertEquals('Gabin Caudan', $response['modifiePar']);
        self::assertEquals(date_format(new DateTime(), 'Y-m-d H:i'), date_format(new DateTime($response['modifieLe']), 'Y-m-d H:i'));
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testEditEvaluationByIdFail(): void
    {
        $response = static::createClient()->request('PUT', self::$BASE_ROUTE.'/edit/50', [
            'json' => [
                'user_id' => 11,
                'idEvent' => 2,
                'statut' => 'Validé',
                'libelle' => 'Evaluation test 2'
            ]
        ]);

        self::assertResponseStatusCodeSame(400);
        self::assertResponseHeaderSame('content-type', 'application/json');
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testRemoveEvaluationById(): void
    {
        $response = static::createClient()->request('DELETE', self::$BASE_ROUTE.'/remove/1');

        self::assertNotEmpty($response);
        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(204);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testRemoveEvaluationByIdFail(): void
    {
        $response = static::createClient()->request('DELETE', self::$BASE_ROUTE.'/remove/50');

        self::assertNotEmpty($response);
        self::assertResponseStatusCodeSame(400);
    }
}
