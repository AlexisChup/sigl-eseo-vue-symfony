<?php

namespace App\DataFixtures;

use App\Entity\CentreFormation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CentreFormationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $cf = new CentreFormation();
        $cf->setNom("ESEO")
        ->setCodePostal(49000)
        ->setAdresse("bd Jean Jeanneteau")
        ->setVille("Angers");

        $manager->persist($cf);

        $this->addReference("cf0",$cf);
        $manager->flush();
    }
}
