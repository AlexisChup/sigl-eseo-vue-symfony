<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use App\Repository\PromotionRepository;
use Exception;
use OpenApi\Annotations as OAnn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/event', name: 'api_event_')]
class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event', methods: 'GET')]
    /**
     * @OAnn\Tag(name="Event")
     */
    public function getEvents(EventRepository $eventRepository): Response
    {
        return $this->json($eventRepository->findAll());
    }

    #[Route('/create', name: 'create_event', methods: 'POST')]
    /**
     * @OAnn\Tag(name="Event")
     */
    public function createEvent(EventRepository $eventRepository, PromotionRepository $promotionRepository, Request $request): Response
    {
        $event = new Event();

        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

            $promo = $promotionRepository->findOneBy(array("id" => $data["promo"]));
            $event->setPromotion($promo);

            $event
                ->setDateDebut((empty($data['debut']) ?: date_create($data['debut'])))
                ->setDateButoir((empty($data['butoir']) ?: date_create($data['butoir'])))
                ->setDateFin((empty($data['fin']) ?: date_create($data['fin'])))
                ->setInitiateur((empty($data['initateur']) ?: $data['initiateur']))
                ->setConcerne((empty($data['concerne']) ?: $data['concerne']))
                ->setRemarque((empty($data['remarque']) ?: $data['remarque']))
                ->setNom($data["nom"])
                ->setType((int)$data["type"]);

            $eventRepository->add($event, true);
            $res = $event;
        } catch (Exception) {
            $res = ["ERROR" => "Erreur lors de la création de l'événement"];
        }
        return $this->json($res);
    }

    #[Route('/{id}', name: 'update_event_by_id', requirements: ['id' => '\d+'], methods: 'PUT')]
    /**
     * @OAnn\Tag(name="Event")
     */
    public function updateEventById(int $id, EventRepository $eventRepository,PromotionRepository $promotionRepository, Request $request): Response
    {
        $eventToUpdate = $eventRepository-> find($id);

        try{

            $data = json_decode($request->getContent(), true);

            $eventToUpdate->setType($data['type']);
            $promo = $promotionRepository->find($data['promo']);
            $eventToUpdate->setPromotion($promo);
            $eventToUpdate->setNom($data['nom']);
            $eventToUpdate->setDateDebut(date_create($data['debut']));
            $eventToUpdate->setDateFin(date_create($data['fin']));
            $eventToUpdate->setRemarque($data['remarque']);
            $eventToUpdate->setInitiateur($data['initiateur']);
            $eventToUpdate->setConcerne($data['concerne']);

            $eventRepository->add($eventToUpdate, true);

            return $this->json($eventToUpdate);

        }catch (e){
            return new Response('Internal Error', 500);
        }
    }

    #[Route('/{id}', name: 'get_event_by_id', requirements: ['id' => '\d+'], methods: 'GET')]
    /**
     * @OAnn\Tag(name="Event")
     */
    public function getEventById(int $id, EventRepository $eventRepository): Response
    {
        return $this->json($eventRepository-> find($id));
    }

    #[Route('/{id}', name: 'remove_event_by_id', requirements: ['id' => '\d+'], methods: 'DELETE')]
    /**
     * @OAnn\Tag(name="Event")
     */
    public function removeEventById(int $id, EventRepository $eventRepository): Response
    {
        $eventToRemove = $eventRepository-> find($id);
        if ($eventToRemove !== null) {
            $eventRepository->remove($eventToRemove, true);
            return $this->json([], 204);
        }
        return $this->json([], 400);
    }

    #[Route('/type/{type_id}', name: 'get_event_by_type', requirements: ['type_id' => '\d+'], methods: 'GET')]
    /**
     * @OAnn\Tag(name="Event")
     */
    public function getEventByType(int $type_id, EventRepository $eventRepository): Response
    {
        $event = $eventRepository->findBy(array('type' => $type_id));
        return $this->json($event);
    }

    #[Route('/livrables/{promo_id}', name: 'get_livrables_promo', requirements: ['promo_id' => '\d+'], methods: 'GET')]
    /**
     * @OAnn\Tag(name="Event")
     */
    public function getLivrablesByPromo(int $promo_id, EventRepository $eventRepository): Response
    {
        $livrables = $eventRepository->findLivrables($promo_id);
        return $this->json($livrables);
    }

    #[Route('/entretiens/{promo_id}', name: 'get_entretiens_promo', requirements: ['promo_id' => '\d+'], methods: 'GET')]
    /**
     * @OAnn\Tag(name="Event")
     */
    public function getEntretiensByPromo(int $promo_id, EventRepository $eventRepository): Response
    {
        $entretiens = $eventRepository->findEntretiens($promo_id);
        return $this->json($entretiens);
    }

    #[Route('/soutenances/{promo_id}', name: 'get_soutenances_promo', requirements: ['promo_id' => '\d+'], methods: 'GET')]
    /**
     * @OAnn\Tag(name="Event")
     */
    public function getSoutenancesByPromo(int $promo_id, EventRepository $eventRepository): Response
    {
        $soutenances = $eventRepository->findSoutenances($promo_id);
        return $this->json($soutenances);
    }
}

