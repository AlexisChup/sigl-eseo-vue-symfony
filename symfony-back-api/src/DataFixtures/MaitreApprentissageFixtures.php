<?php

namespace App\DataFixtures;

use App\Entity\MaitreApprentissage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MaitreApprentissageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $numberOfCompany = 0;
        for ($i = 17; $i < 25; $i++) {
            $ma = new MaitreApprentissage();
            $ma->setUtilisateur($this->getReference("utilisateur" . $i));
            $ma->setEntreprise($this->getReference("entreprise" . $numberOfCompany));
            $manager->persist($ma);

            $this->addReference("ma" . $i, $ma);
            $numberOfCompany++;
            $numberOfCompany %= 10;
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UtilisateurFixtures::class,
            EntrepriseFixtures::class,
        ];
    }
}
