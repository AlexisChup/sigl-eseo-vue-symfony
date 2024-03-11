<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Generator;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UtilisateurControllerTest extends ApiTestCase
{
	private static String $BASE_ROUTE = "/api/utilisateur";

	/**
	 * @throws TransportExceptionInterface
	 * @throws ServerExceptionInterface
	 * @throws RedirectionExceptionInterface
	 * @throws DecodingExceptionInterface
	 * @throws ClientExceptionInterface
	 * @covers \App\Controller\UtilisateurController::getUtilisateurs
	 */
	public function testGetUtilisateurs(): void
    {
	    $response = static::createClient()->request('GET', self::$BASE_ROUTE.'/all');

        self::assertResponseIsSuccessful();
		self::assertResponseStatusCodeSame(200);
	    self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertNotEmpty($response->toArray());

		// On ne teste pas le contenu car le but ici est de savoir si on a bien tous les utilisateurs récupérés
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws \Exception
     * @covers \App\Controller\UtilisateurController::createUtilisateur
     */
	public function testCreateUtilisateurNonExistant(): void
	{
		// Insertion d'un utilisateur non existant
		$response = static::createClient()->request('POST', self::$BASE_ROUTE.'/create', ['json' => [
			'nom' => 'Guiheu',
			'prenom' => 'Jade',
			'motDePasse' => 'jg',
			'tel' => '0123456789',
			'adresse' => '1 rue de la rue',
			'email' => 'z.z@reseau.eseo.fr',
			'actif' => true
		]])->toArray();

		self::assertResponseIsSuccessful();
		self::assertResponseStatusCodeSame(200);
		self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertEquals('Guiheu', $response['nom']);
		self::assertEquals('Jade', $response['prenom']);
		self::assertEquals('0123456789', $response['telephone']);
		self::assertEquals('1 rue de la rue', $response['adresse']);
		self::assertEquals('z.z@reseau.eseo.fr', $response['email']);
		self::assertTrue($response['actif']);
	}

	/**
	 * @throws TransportExceptionInterface
	 * @throws ServerExceptionInterface
	 * @throws RedirectionExceptionInterface
	 * @throws DecodingExceptionInterface
	 * @throws ClientExceptionInterface
	 * @covers \App\Controller\UtilisateurController::createUtilisateur
	 */
	public function testCreateUtilisateurExistant(): void
	{
		// Insertion d'un utilisateur existant (même qu'avant)
		$response = static::createClient()->request('POST', self::$BASE_ROUTE.'/create', ['json' => [
			'nom' => 'Guiheu',
			'prenom' => 'Jade',
			'motDePasse' => 'jg',
			'tel' => '0123456789',
			'adresse' => '1 rue de la rue',
			'email' => 'a.b@reseau.eseo.fr',
			'actif' => true
		]]);

		self::assertResponseIsSuccessful();
		self::assertResponseStatusCodeSame(200);
		self::assertResponseHeaderSame('content-type', 'application/json');
		self::assertJsonContains([]);
	}

	/**
	 * @throws TransportExceptionInterface
	 * @throws ServerExceptionInterface
	 * @throws RedirectionExceptionInterface
	 * @throws DecodingExceptionInterface
	 * @throws ClientExceptionInterface
	 * @covers \App\Controller\UtilisateurController::getAllApprentis
	 */
	public function testGetApprentis(): void
	{
		$response = static::createClient()->request('GET', self::$BASE_ROUTE."/apprentis");

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
	 * @covers \App\Controller\UtilisateurController::getAllEnseignants
	 */
	public function testGetEnseignants(): void
	{
		$response = static::createClient()->request('GET', self::$BASE_ROUTE.'/enseignants');

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
	 * @covers \App\Controller\UtilisateurController::getRights
	 */
	public function testGetRightsAdmin(): void
	{
		$response = static::createClient()->request('GET', self::$BASE_ROUTE.'/1/rights');

		self::assertResponseIsSuccessful();
		self::assertResponseStatusCodeSame(200);
		self::assertResponseHeaderSame('content-type', 'application/json');
		self::assertJsonContains(['role' => 'ADMIN']);
	}

	/**
	 * @throws TransportExceptionInterface
	 * @throws ServerExceptionInterface
	 * @throws RedirectionExceptionInterface
	 * @throws DecodingExceptionInterface
	 * @throws ClientExceptionInterface
	 * @covers \App\Controller\UtilisateurController::getRights
	 */
	public function testGetRightsApprenti(): void
	{
		$response = static::createClient()->request('GET', self::$BASE_ROUTE.'/10/rights');

		self::assertResponseIsSuccessful();
		self::assertResponseStatusCodeSame(200);
		self::assertResponseHeaderSame('content-type', 'application/json');
		self::assertJsonContains(['role' => 'APPRENTI']);
	}

	/**
	 * @throws TransportExceptionInterface
	 * @throws ServerExceptionInterface
	 * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
	 * @covers \App\Controller\UtilisateurController::getRights
	 */
	public function testGetRightsCoordinateur(): void
	{
		$response = static::createClient()->request('GET', self::$BASE_ROUTE.'/2/rights');

		self::assertResponseIsSuccessful();
		self::assertResponseStatusCodeSame(200);
		self::assertResponseHeaderSame('content-type', 'application/json');
	}

	/**
	 * @throws TransportExceptionInterface
	 * @throws ServerExceptionInterface
	 * @throws RedirectionExceptionInterface
	 * @throws DecodingExceptionInterface
	 * @throws ClientExceptionInterface
	 * @covers \App\Controller\UtilisateurController::getRights
	 */
	public function testGetRightsEnseignant(): void
	{
		$response = static::createClient()->request('GET', self::$BASE_ROUTE.'/4/rights');

		self::assertResponseIsSuccessful();
		self::assertResponseStatusCodeSame(200);
		self::assertResponseHeaderSame('content-type', 'application/json');
		self::assertJsonContains(['role' => 'ENSEIGNANT']);
	}

	/**
	 * @throws TransportExceptionInterface
	 * @throws ServerExceptionInterface
	 * @throws RedirectionExceptionInterface
	 * @throws DecodingExceptionInterface
	 * @throws ClientExceptionInterface
	 * @covers \App\Controller\UtilisateurController::getRights
	 */
	public function testGetRightsMA(): void
	{
		$response = static::createClient()->request('GET', self::$BASE_ROUTE.'/18/rights');

		self::assertResponseIsSuccessful();
		self::assertResponseStatusCodeSame(200);
		self::assertResponseHeaderSame('content-type', 'application/json');
		self::assertJsonContains(['role' => 'MAITRE-APPRENTISSAGE']);
	}

	/**
	 * @throws TransportExceptionInterface
	 * @throws ServerExceptionInterface
	 * @throws RedirectionExceptionInterface
	 * @throws DecodingExceptionInterface
	 * @throws ClientExceptionInterface
	 * @covers \App\Controller\UtilisateurController::getRights
	 */
	public function testGetRightsNone(): void
	{
		$response = static::createClient()->request('GET', self::$BASE_ROUTE.'/50/rights');

		self::assertResponseIsSuccessful();
		self::assertResponseStatusCodeSame(200);
		self::assertResponseHeaderSame('content-type', 'application/json');
		self::assertJsonContains(['role' => 'UNIDENTIFIED']);
	}

	/**
	 * @throws TransportExceptionInterface
	 * @throws ServerExceptionInterface
	 * @throws RedirectionExceptionInterface
	 * @throws DecodingExceptionInterface
	 * @throws ClientExceptionInterface
	 * @covers \App\Controller\UtilisateurController::getUtilisateurById
	 * @dataProvider userIdsProvider
	 */
	public function testGetUtilisateurById($id): void
	{
		$response = static::createClient()->request('GET', self::$BASE_ROUTE.'/'.$id);

		self::assertResponseIsSuccessful();
		self::assertResponseStatusCodeSame(200);
		self::assertResponseHeaderSame('content-type', 'application/json');

		switch ($id) {
			case 1:
				self::assertJsonContains(['nom' => 'Margas-Leroyer']);
				self::assertJsonContains(['prenom' => 'Julie']);
				self::assertJsonContains(['email' => 'julie.margas-leroyer@reseau.eseo.fr']);
				self::assertJsonContains(['actif' => true]);
				break;

			case 2:
				self::assertJsonContains(['nom' => 'Hammoudi']);
				self::assertJsonContains(['prenom' => 'Slimane']);
				self::assertJsonContains(['email' => 'slimane.hammoudi@reseau.eseo.fr']);
				self::assertJsonContains(['actif' => true]);
				break;

			case 3:
				self::assertJsonContains(['nom' => 'Bilel']);
				self::assertJsonContains(['prenom' => 'Faker']);
				self::assertJsonContains(['email' => 'faker.bilel@reseau.eseo.fr']);
				self::assertJsonContains(['actif' => true]);
				break;

			case 4:
				self::assertJsonContains(['nom' => 'Maïssa']);
				self::assertJsonContains(['prenom' => 'Abdallah']);
				self::assertJsonContains(['email' => 'abdallah.maïssa@reseau.eseo.fr']);
				self::assertJsonContains(['actif' => true]);
				break;

			case 5:
				self::assertJsonContains(['nom' => 'Reynaud']);
				self::assertJsonContains(['prenom' => 'Jean-Samuel']);
				self::assertJsonContains(['email' => 'jean-samuel.reynaud@reseau.eseo.fr']);
				self::assertJsonContains(['actif' => true]);
				break;
		}
		// Les numéros de téléphone et les adresses ne sont pas testées car ce sont des chiffres aléatoires
	}

	public function userIdsProvider(): Generator
	{
		yield [1];
		yield [2];
		yield [3];
		yield [4];
		yield [5];
	}

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @covers \App\Controller\UtilisateurController::getRoleFromUtilisateur
     * @dataProvider userIdsRightsProvider
     */
    public function testGetRoleFromUtilisateur($id): void
    {
        $response = static::createClient()->request('GET', self::$BASE_ROUTE . "/$id/role");

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
        self::assertResponseHeaderSame('content-type', 'application/json');

        switch ($id) {
            case 1:
                self::assertJsonContains(['role' => 'ADMIN']);
                break;

            case 5:
                self::assertJsonContains(['role' => 'ENSEIGNANT']);
                break;

            case 10:
                self::assertJsonContains(['role' => 'APPRENTI']);
                break;

            case 18:
                self::assertJsonContains(['role' => 'MAITRE-APPRENTISSAGE']);
                break;

            case 50:
                self::assertJsonContains(['role' => 'UNIDENTIFIED']);
                break;
        }
    }

    public function userIdsRightsProvider(): Generator
    {
        yield [1];  // ADMIN
        yield [5];  // ENSEIGNANT
        yield [10]; // APPRENTI
        yield [18]; // MAITRE-APPRENTISSAGE
        yield [50]; // UNIDENTIFIED
        // Pas de test pour COORDINATEUR car personne avec ce rôle unique (confondu avec Admin)
    }
}
