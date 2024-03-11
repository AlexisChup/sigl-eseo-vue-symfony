<?php

namespace App\Controller;

use App\Entity\Apprenti;
use App\Entity\Entretien;
use App\Entity\Event;
use App\Repository\EntretienRepository;
use Doctrine\Persistence\ManagerRegistry;
use OpenApi\Annotations as OAnn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/entretien', name: 'api_entretien_')]
class EntretienController extends AbstractController
{
    #[Route('/entretien', name: 'app_entretien', methods: 'GET')]
    /**
     * @OAnn\Tag(name="Entretien")
     */
    public function getEntretiens(EntretienRepository $entretienRepository): Response
    {
	    return $this->json($entretienRepository->findAll());
    }

    #[Route('/create', name: 'create_entretien', methods: ["POST"])]
    /**
     * @OAnn\Tag(name="Entretien")
     */
    public function createEntretien(EntretienRepository $entretienRepository, Request $http, managerRegistry $manager): Response
    {
        $eventRepo = $manager->getRepository(Event::class);
        $apprentiRepo = $manager->getRepository(Apprenti::class);
        $entretien = new Entretien();

        try {
            $data = json_decode($http->getContent(), true);

            $event = $eventRepo->find($data["event"]);
            $entretien->setEvent($event);


            $entretien->setTitle($data["title"]);
            $data["date"] = date_create($data["date"]);
            $entretien->setDate($data["date"]);
            $entretien->setInformation($data["information"]);
            $entretien->setLocation($data["location"]);
            $data["time"] = date_create($data["time"]);
            $entretien->setTime($data["time"]);

            $entretienRepository->add($entretien, true);

            $res=$entretien;
        } catch (e) {
            $res="entretien-non-ajoutee";
        }
        return $this->json($res);
    }
}
