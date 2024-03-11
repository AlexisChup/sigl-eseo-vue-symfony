<?php

namespace App\DataFixtures;

use App\Entity\Apprenti;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ApprentiFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $numEnseignant = 1;
        $numMa = 17;
        $promoId = 1;
        for ($i = 9; $i < 17; $i++) {
            $apprenti = new Apprenti();
            $apprenti->setUtilisateur($this->getReference("utilisateur" . $i));
            $apprenti->setEnseignant($this->getReference("enseignant" . $numEnseignant));
            $apprenti->setMaitreApprentissage($this->getReference("ma" . $numMa));
            $apprenti->setIdPromotion($this->getReference("promotion".$promoId));
            $manager->persist($apprenti);
            $numEnseignant++;
            $numEnseignant %= 8 + 1;
            $numMa++;
            $numMa %= 8 + 17;


            $this->addReference("apprenti".$i,$apprenti);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return array(
            EnseignantFixtures::class,
            MaitreApprentissageFixtures::class,
            UtilisateurFixtures::class,
        );
    }
}
