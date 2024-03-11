<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Soutenance;
use App\Repository\SoutenanceRepository;
use OpenApi\Annotations as OAnn;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/soutenance', name: 'api_soutenance')]
class SoutenanceController extends AbstractController
{
    #[Route('/soutenances', name: 'app_soutenance', methods: 'GET')]
    /**
     * @OAnn\Tag(name="Soutenance")
     */
    public function getSoutenances(SoutenanceRepository $soutenanceRepository): Response
    {
	    return $this->json($soutenanceRepository->findAll());
    }

    #[Route('/create', name: 'create_soutenance', methods: ["POST"])]
    /**
     * @OAnn\Tag(name="Soutenance")
     */
    public function createSoutenance(SoutenanceRepository $soutenanceRepository, Request $http, managerRegistry $manager): Response
    {
        $eventRepo = $manager->getRepository(Event::class);
        $soutenance = new Soutenance();

        try {
            $data = json_decode($http->getContent(), true);
            $event = $eventRepo->find($data["event"]);
            $soutenance->setEvent($event);
            $soutenance->setTitle($data["title"]);
            $data["date"] = date_create($data["date"]);
            $soutenance->setDate($data["date"]);
            $soutenance->setInformation($data["information"]);
            $soutenance->setLocation($data["location"]);
            $data["time"] = date_create($data["time"]);
            $soutenance->setTime($data["time"]);

            $soutenanceRepository->add($soutenance, true);

            $res=$soutenance;
        } catch (e) {
            $res="soutenance-non-ajoutee";
        }
        return $this->json($res);
    }
}
