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

class NotesPeriodeControllerTest extends ApiTestCase
{
    private static String $BASE_ROUTE = "/api/notes-periode";

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testCreateNotesPeriodeSuccess(): void
    {
        $response = static::createClient()->request('POST', self::$BASE_ROUTE.'/create', [
            'json' => [
                'titre' => 'Note du 20 au 27 janvier',
                'contenu' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris mollis lectus non nulla sodales, vitae ultricies massa varius. Nam ut nulla in nisi malesuada rhoncus. Phasellus non imperdiet quam. Aenean nulla lorem, feugiat nec commodo at, ultrices ac dui. Integer non posuere libero, vel molestie mauris.',
                'semestre' => '5',
                'user_id' => '10'
            ]
        ])->toArray();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
        self::assertNotEmpty($response);

        self::assertNotEmpty($response['apprenti']);
        self::assertNotEmpty($response['apprenti']['utilisateur']);
        self::assertNotEmpty($response['apprenti']['enseignant']);
        self::assertNotEmpty($response['apprenti']['enseignant']['centreFormation']);
        self::assertNotEmpty($response['apprenti']['maitreApprentissage']);
        self::assertNotEmpty($response['apprenti']['idPromotion']);

        self::assertNotEmpty($response['titre']);
        self::assertIsString($response['titre']);
        self::assertNotEmpty($response['contenu']);
        self::assertIsString($response['contenu']);
        self::assertNotEmpty($response['date']);
        self::assertIsString($response['date']);

        self::assertEquals("Note du 20 au 27 janvier", $response['titre']);
        self::assertEquals("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris mollis lectus non nulla sodales, vitae ultricies massa varius. Nam ut nulla in nisi malesuada rhoncus. Phasellus non imperdiet quam. Aenean nulla lorem, feugiat nec commodo at, ultrices ac dui. Integer non posuere libero, vel molestie mauris.", $response['contenu']);
        self::assertEquals(date_format(new DateTime(), 'Y-m-d H:i'), date_format(new DateTime($response['date']), 'Y-m-d H:i'));
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testCreateNotesPeriodeFail(): void
    {
        $response = static::createClient()->request('POST', self::$BASE_ROUTE.'/create', [
            'json' => [
                'titre' => 111111111111111,
                'contenu' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris mollis lectus non nulla sodales, vitae ultricies massa varius. Nam ut nulla in nisi malesuada rhoncus. Phasellus non imperdiet quam. Aenean nulla lorem, feugiat nec commodo at, ultrices ac dui. Integer non posuere libero, vel molestie mauris.',
                'semestre' => 387497,
                'user_id' => '10000'
            ]
        ]);

        self::assertResponseStatusCodeSame(400);
        self::assertNotEmpty($response);
        self::assertJsonContains(["ERROR" => "Erreur lors de la création de la note périodique"]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testGetNotePeriode(): void
    {
        $response = static::createClient()->request('GET', self::$BASE_ROUTE.'/1')->toArray();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
        self::assertNotEmpty($response);

        self::assertNotEmpty($response['apprenti']);
        self::assertNotEmpty($response['apprenti']['utilisateur']);
        self::assertNotEmpty($response['apprenti']['enseignant']);
        self::assertNotEmpty($response['apprenti']['enseignant']['centreFormation']);
        self::assertNotEmpty($response['apprenti']['maitreApprentissage']);
        self::assertNotEmpty($response['apprenti']['idPromotion']);

        self::assertNotEmpty($response['titre']);
        self::assertIsString($response['titre']);
        self::assertNotEmpty($response['contenu']);
        self::assertIsString($response['contenu']);
        self::assertNotEmpty($response['date']);
        self::assertIsString($response['date']);

        self::assertEquals("Note du 20 au 27 janvier", $response['titre']);
        self::assertEquals("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris mollis lectus non nulla sodales, vitae ultricies massa varius. Nam ut nulla in nisi malesuada rhoncus. Phasellus non imperdiet quam. Aenean nulla lorem, feugiat nec commodo at, ultrices ac dui. Integer non posuere libero, vel molestie mauris.", $response['contenu']);
        self::assertEquals(date_format(new DateTime(), 'Y-m-d H:i'), date_format(new DateTime($response['date']), 'Y-m-d H:i'));
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @dataProvider apprentisUserIdProvider
     */
    public function testGetNotesPeriodeApprenti($id): void
    {
        $response = static::createClient()->request('GET', self::$BASE_ROUTE.'/from/'.$id);

        self::assertNotEmpty($response);

        switch ($id) {
            case 10:
                $response = $response->toArray()[0];

                self::assertResponseIsSuccessful();
                self::assertResponseStatusCodeSame(200);

                self::assertNotEmpty($response['titre']);
                self::assertIsString($response['titre']);
                self::assertNotEmpty($response['contenu']);
                self::assertIsString($response['contenu']);
                self::assertNotEmpty($response['date']);
                self::assertIsString($response['date']);
                self::assertNotEmpty($response['semestre']);
                self::assertIsString($response['semestre']);
                break;

            case 50:
                self::assertResponseStatusCodeSame(400);
                break;
        }
    }

    public function apprentisUserIdProvider(): Generator
    {
        yield [10];
        yield [50];
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testUpdateNotePeriodeSuccess(): void
    {
        $response = static::createClient()->request('PUT', self::$BASE_ROUTE.'/update/1', [
            'json' => [
                'titre' => 'Note du 20 janvier au 3 février',
                'contenu' => 'Pas de lorem ipsum cette fois'
            ]
        ])->toArray();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
        self::assertNotEmpty($response);

        self::assertEquals('Note du 20 janvier au 3 février', $response['titre']);
        self::assertEquals('Pas de lorem ipsum cette fois', $response['contenu']);
        self::assertEquals(date_format(new DateTime(), 'Y-m-d H:i'), date_format(new DateTime($response['date']), 'Y-m-d H:i'));
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testUpdateNotePeriodeFail(): void
    {
        $response = static::createClient()->request('PUT', self::$BASE_ROUTE.'/update/1000', [
            'json' => [
                'titre' => 'Note du 20 janvier au 3 février',
                'contenu' => 'Pas de lorem ipsum cette fois'
            ]
        ]);

        self::assertResponseStatusCodeSame(400);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testDeleteNotePeriodeSuccess(): void
    {
        $response = static::createClient()->request('DELETE', self::$BASE_ROUTE.'/delete/1');

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(204);
    }

    public function testDeleteNotePeriodeFail(): void
    {
        $response = static::createClient()->request('DELETE', self::$BASE_ROUTE.'/delete/1000');

        self::assertResponseStatusCodeSame(400);
    }
}
