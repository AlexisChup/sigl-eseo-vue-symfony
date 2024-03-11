<?php

namespace App\DataFixtures;

use App\Entity\Event;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Exception;
use Faker\Factory;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        // Type 0 = Livrable
        for($i = 0; $i < 6; $i++) {
            $event = new Event();
            $event->setPromotion($this->getReference("promotion" . 1))
                ->setNom('S'.($i+5).' - Rapport semestriel')
                ->setDateDebut(new \DateTime("2022/11/" . $i + 7))
                ->setDateFin(new \DateTime("2022/11/" . $i + 7))
                ->setInitiateur("Julie")
                ->setConcerne("Moreau")
                ->setRemarque("")
                ->setType(0);
            $manager->persist($event);

            $this->addReference("event".$i,$event);
        }

        // Type 1 = Entretien semestriel
        for($i = 7; $i < 13; $i++) {
            $event = new Event();
            $event
                ->setPromotion($this->getReference("promotion" . 2))
                ->setNom('S'.($i+5-7).' - Entretien semestriel')
                ->setDateDebut(new DateTime("2022/12/".$i+6))
                ->setDateFin(new DateTime("2022/12/".$i+8))
                ->setInitiateur("Charles")
                ->setConcerne("Collins")
                ->setRemarque("")
                ->setType(1);
            $manager->persist($event);

            $this->addReference("event".$i, $event);
        }

        // Type 2 = Soutenance
        $event_soutenance_S7 = new Event();
        $event_soutenance_S7
            ->setPromotion($this->getReference("promotion" . 3))
            ->setNom("S7 - Soutenance technique")
            ->setDateDebut(new DateTime("2023/01/10"))
            ->setDateFin(new DateTime("2023/01/10"))
            ->setInitiateur("Moi")
            ->setConcerne("Toi")
            ->setRemarque("Comment ça la grille est la même depuis 10 ans")
            ->setType(2);
        $manager->persist($event_soutenance_S7);

        $event_soutenance_S8 = new Event();
        $event_soutenance_S8
            ->setPromotion($this->getReference("promotion" . 4))
            ->setNom("S8 - Soutenance PréPING")
            ->setDateDebut(new DateTime("2023/01/17"))
            ->setDateFin(new DateTime("2023/01/17"))
            ->setInitiateur("Toi")
            ->setConcerne("Moi")
            ->setRemarque("Pas de planning = mort")
            ->setType(2);
        $manager->persist($event_soutenance_S8);

        $event_soutenance_S10 = new Event();
        $event_soutenance_S10
            ->setPromotion($this->getReference("promotion" . 4))
            ->setNom("S10 - Soutenance PING")
            ->setDateDebut(new DateTime("2023/01/24"))
            ->setDateFin(new DateTime("2023/01/24"))
            ->setInitiateur("Nous")
            ->setConcerne("C'est moooi")
            ->setRemarque("Le diplome")
            ->setType(2);
        $manager->persist($event_soutenance_S10);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return array(
           PromotionFixtures::class,
        );
    }


}
