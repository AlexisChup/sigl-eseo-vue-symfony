<?php

namespace App\DataFixtures;

use App\Entity\Evaluation;
use App\Entity\Livrable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class LivrableFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

    }

    public function getDependencies(): array
    {
        return array(
           PromotionFixtures::class,
           EvaluationFixtures::class,
           EventFixtures::class,
        );
    }


}
