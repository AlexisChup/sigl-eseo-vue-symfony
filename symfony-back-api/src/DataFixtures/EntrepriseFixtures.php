<?php

namespace App\DataFixtures;

use App\Entity\Entreprise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EntrepriseFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i < 10; $i++){
            $faker = Factory::create("fr_FR");

            $entreprise = new Entreprise();
            $entreprise->setNom($faker->company)
            ->setVille($faker->city)
            ->setAdresse($faker->streetName)
            ->setCodePostal($faker->numberBetween(10000,95100));

            $manager->persist($entreprise);

            $this->addReference("entreprise".$i,$entreprise);
        }
        $manager->flush();
    }
}
