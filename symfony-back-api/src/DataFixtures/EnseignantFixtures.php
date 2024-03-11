<?php

namespace App\DataFixtures;

use App\Entity\CentreFormation;
use App\Entity\Enseignant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EnseignantFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 9; $i++) {
            $enseignant = new Enseignant();
            $enseignant->setUtilisateur($this->getReference("utilisateur" . $i));
            $enseignant->setCentreFormation($this->getReference("cf0"));
            $manager->persist($enseignant);

            $this->addReference("enseignant" . $i, $enseignant);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UtilisateurFixtures::class,
            CentreFormationFixtures::class,
        ];
    }
}
