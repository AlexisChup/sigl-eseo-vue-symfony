<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Utilisateur;
use Faker\Factory;

class UtilisateurFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");
        $utilisateurs = array(
            // Coordnatrice
            array("Julie", "Margas-Leroyer"), // Admin

            // Enseignant
            array("Slimane", "Hammoudi"), // Admin
            array("Faker", "Bilel"),
            array("Abdallah", "Maïssa"),
            array("Jean-Samuel", "Reynaud"),
            array("Ferret-Canapé", "Clive"),
            array("Laurence", "Guilot"),
            array("Scotto", "Sebastien"),
            array("Phillipe", "Riffoux"),

            // Apprentis
            array("Simon", "Anneau"),
            array("Gabin", "Caudan"),
            array("Alexis", "Chupin"),
            array("Melvin", "Gasté"),
            array("Jade", "Guiheu"),
            array("Loris", "Le Bris"),
            array("Maxime", "Mahieu"),
            array("Tony", "Pasquier"),

            // Maitre d'apprentissage
            array($faker->lastname, $faker->lastname),
            array($faker->lastname, $faker->lastname),
            array($faker->lastname, $faker->lastname),
            array($faker->lastname, $faker->lastname),
            array($faker->lastname, $faker->lastname),
            array($faker->lastname, $faker->lastname),
            array($faker->lastname, $faker->lastname),
            array($faker->lastname, $faker->lastname),
        );

        for ($i = 0; $i < 25; $i++) {

            $tel = "0" . (random_int(600000000, 799999999));
            $numAdresse = random_int(1, 299);

            $utilisateur = new Utilisateur();

            $passwordClear = strtolower(substr($utilisateurs[$i][0], 0, 1) . substr($utilisateurs[$i][1], 0, 1));
            $encryptedPassword = md5($passwordClear);

            $utilisateur->setPrenom($utilisateurs[$i][0])
                ->setNom($utilisateurs[$i][1])
                ->setEmail(strtolower($utilisateurs[$i][0] . "." . $utilisateurs[$i][1]) . "@reseau.eseo.fr")
                ->setMotDePasse($encryptedPassword)
                ->setActif(true)
                ->setTelephone($tel)
                ->setAdresse($numAdresse . " Boulevard Jean Jeanneteau, 49100 Angers");


            $manager->persist($utilisateur);

            $this->addReference("utilisateur" . $i, $utilisateur);
        }

        $manager->flush();
    }
}
