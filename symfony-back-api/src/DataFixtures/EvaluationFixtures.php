<?php

namespace App\DataFixtures;

use App\Entity\Evaluation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class EvaluationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

    }

    public function getDependencies(): array
    {
        return array(
           PromotionFixtures::class,
           ApprentiFixtures::class,
           EventFixtures::class,
        );
    }


}
