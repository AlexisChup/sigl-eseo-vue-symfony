<?php

namespace App\DataFixtures;

use App\Entity\Coordinateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CoordinateurFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 2; $i++) {
            $coordinateur = new Coordinateur();
            $coordinateur->setUtilisateur($this->getReference("utilisateur" . $i));
            $manager->persist($coordinateur);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UtilisateurFixtures::class
        ];
    }
}
