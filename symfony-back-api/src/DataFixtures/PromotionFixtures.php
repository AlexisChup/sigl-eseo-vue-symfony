<?php

namespace App\DataFixtures;

use App\Entity\Promotion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PromotionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i < 5; $i++){
            $faker = Factory::create("fr_FR");

            $promo = new Promotion();
            $promo->setNom($faker->lastName)
            ->setAnnee(2020+$i);

            $manager->persist($promo);

            $this->addReference("promotion".$i,$promo);
        }

        $manager->flush();
    }
}
