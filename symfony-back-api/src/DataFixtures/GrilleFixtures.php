<?php

namespace App\DataFixtures;

use App\Entity\Grille;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GrilleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 5; $i <= 10; $i++) {
            $grille = new Grille();
            $grille->setNom('Semestre ' .$i);

            $manager->persist($grille);
            $this->addReference('grille' . $i, $grille);
        }

        $manager->flush();
    }
}
