<?php


namespace App\Tests\Entity;

use App\Entity\Utilisateur;
use PHPUnit\Framework\TestCase;

class   UtilisateurTest extends TestCase
{
	/**
	 * @covers \App\Entity\Utilisateur::getId
	 * @covers \App\Entity\Utilisateur::getNom
	 * @covers \App\Entity\Utilisateur::getPrenom
	 * @covers \App\Entity\Utilisateur::getAdresse
	 * @covers \App\Entity\Utilisateur::getEmail
	 * @covers \App\Entity\Utilisateur::getMotDePasse
	 * @covers \App\Entity\Utilisateur::getTelephone
	 * @covers \App\Entity\Utilisateur::isActif
	 */
	public function testDefaultUtilisateur(): void
	{
		$Utilisateur = new Utilisateur();

		// Test des valeurs null par défaut
		$this->assertNull($Utilisateur->getId());
		$this->assertNull($Utilisateur->getNom());
		$this->assertNull($Utilisateur->getPrenom());
		$this->assertNull($Utilisateur->getAdresse());
		$this->assertNull($Utilisateur->getEmail());
		$this->assertNull($Utilisateur->getMotDePasse());
		$this->assertNull($Utilisateur->getTelephone());
		$this->assertNull($Utilisateur->isActif());
	}

	/**
	 * @covers \App\Entity\Utilisateur::setNom
	 * @covers \App\Entity\Utilisateur::setPrenom
	 * @covers \App\Entity\Utilisateur::setAdresse
	 * @covers \App\Entity\Utilisateur::setEmail
	 * @covers \App\Entity\Utilisateur::setMotDePasse
	 * @covers \App\Entity\Utilisateur::setTelephone
	 * @covers \App\Entity\Utilisateur::setActif
	 * @covers \App\Entity\Utilisateur::getId
	 * @covers \App\Entity\Utilisateur::getNom
	 * @covers \App\Entity\Utilisateur::getPrenom
	 * @covers \App\Entity\Utilisateur::getAdresse
	 * @covers \App\Entity\Utilisateur::getEmail
	 * @covers \App\Entity\Utilisateur::getMotDePasse
	 * @covers \App\Entity\Utilisateur::getTelephone
	 * @covers \App\Entity\Utilisateur::isActif
	 */
	public function testCreatedUtilisateur(): void
	{
		$Utilisateur = new Utilisateur();

		$Utilisateur->setNom("Mahieu");
		$Utilisateur->setPrenom("Maxime");
		$Utilisateur->setAdresse("A la maison");
		$Utilisateur->setEmail("a.b@gmail.com");
		$Utilisateur->setMotDePasse("password");
		$Utilisateur->setTelephone("0123456789");
		$Utilisateur->setActif(true);

		$this->assertNull($Utilisateur->getId());
		$this->assertEquals("Mahieu", $Utilisateur->getNom());
		$this->assertEquals("Maxime", $Utilisateur->getPrenom());
		$this->assertEquals("A la maison", $Utilisateur->getAdresse());
		$this->assertEquals("a.b@gmail.com", $Utilisateur->getEmail());
		$this->assertEquals("password", $Utilisateur->getMotDePasse());
		$this->assertEquals("0123456789", $Utilisateur->getTelephone());
		$this->assertTrue($Utilisateur->isActif());
	}
}
