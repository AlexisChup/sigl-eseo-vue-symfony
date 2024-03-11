<?php

namespace App\Controller;

use App\Entity\Apprenti;
use App\Entity\Document;
use App\Entity\Evaluation;
use App\Entity\Event;
use App\Repository\EvaluationRepository;
use DateTime;
use Exception;
use JsonException;
use OpenApi\Annotations as OAnn;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/evaluation', name: 'api_evaluation_')]
class EvaluationController extends AbstractController
{
    #[Route('/evaluations', name: 'app_evaluation', methods: 'GET')]
    /**
     * @OAnn\Tag(name="Evaluation")
     */
    public function getAllEvaluations(EvaluationRepository $evaluationRepository): Response
    {
	    return $this->json($evaluationRepository->findAll());
    }

    #[Route('/edit/{id}', name: 'edit_evaluation_by_id', methods: 'PUT', requirements: ['id' => '\d+'])]
    /**
     * @OAnn\Tag(name="Evaluation")
     * @throws JsonException
     */
    public function editEvaluationById(int $id, EvaluationRepository $evaluationRepository, Request $http, ManagerRegistry $manager): Response
    {
        $data = json_decode($http->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $appRepo = $manager->getRepository(Apprenti::class);

        $app = $appRepo->getUtilisateur((int)$data["user_id"])[0];
        $eval = $evaluationRepository->find($id);

        if ($eval !== null && $app !== null) {
            $eval->setStatut($data['statut']);
            $eval->setModifieLe(new DateTime());
            $eval->setModifiePar($app->getUtilisateur()->getPrenom().' '.$app->getUtilisateur()->getNom());
            $eval->setLibelle($data['libelle']);

            $evaluationRepository->add($eval, true);
            return $this->json($eval);
        }
        return $this->json([], 400);
    }

    #[Route('/create', name: 'create_evaluation', methods: 'POST')]
    /**
     * @OAnn\Tag(name="Evaluation")
     * @throws JsonException
     */
    public function createEvaluation(EvaluationRepository $evaluationRepository, Request $request, ManagerRegistry $manager): Response
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $appRepo = $manager->getRepository(Apprenti::class);
        $eventRepo = $manager->getRepository(Event::class);
        $docRepo = $manager->getRepository(Document::class);

        $event = null;
        $doc = null;

        $app = $appRepo->getUtilisateur((int)$data["user_id"])[0];

        if (array_key_exists('idEvent', $data)) {
            $event = $eventRepo->find($data['idEvent']);
        }

        if (array_key_exists('idDoc', $data)) {
            $doc = $docRepo->find($data['idDoc']);
        }

        $eval = new Evaluation();

        try {
            if ($app !== null) {
                $eval->setStatut($data['statut']);
                $eval->setModifieLe(new DateTime());
                $eval->setModifiePar($app->getUtilisateur()->getPrenom().' '.$app->getUtilisateur()->getNom());
                $eval->setLibelle($data['libelle']);
                $eval->setApprenti($app);

                if ($doc !== null) {
                    $eval->setDocument($doc);
                }

                if($event !== null){
                    $eval->setEvent($event);
                }

                $evaluationRepository->add($eval, true);
                return $this->json($eval);
            }
        } catch (Exception) {
            return $this->json(null, 400);
        }
        return $this->json(null, 400);
    }

    #[Route('/remove/{id}', name: 'remove_evaluation_by_id', requirements: ['id' => '\d+'], methods: 'DELETE')]
    /**
     * @OAnn\Tag(name="Evaluation")
     */
    public function removeEvaluationById(int $id, EvaluationRepository $evaluationRepository): Response
    {
        $evalToRemove = $evaluationRepository->find($id);

        if ($evalToRemove !== null) {
            if ($evalToRemove->getDocument() !== null) {
                unlink($evalToRemove->getDocument()->getPath(), null);
            }
            $evaluationRepository->remove($evalToRemove, true);
            return $this->json([], 204);
        }
        return $this->json([], 400);
    }

    #[Route('/{id}', name: 'get_evaluation_by_id', requirements: ['id' => '\d+'], methods: 'GET')]
    /**
     * @OAnn\Tag(name="Evaluation")
     */
    public function getEvaluationById(int $id, EvaluationRepository $evaluationRepository): Response
    {
        return $this->json($evaluationRepository->find($id));
    }

    #[Route('from-app/{id}', name: 'get_evaluation_by_app_id', methods: 'GET')]
    /**
     * @OAnn\Tag(name="Evaluation")
     */
    public function getEvaluationByAppId(int $id, EvaluationRepository $evaluationRepository): Response
    {
        return  $this->json($id);
        return $this->json($evaluationRepository->findBy(array("apprenti" => $id)));
    }

    #[Route('/livrables/{uid}', name: 'livrables_from_eval', methods: 'GET', requirements: ['uid' => '\d+'])]
    /**
     * @OAnn\Tag(name="Evaluation")
     */
    public function getLivrables(EvaluationRepository $evaluationRepository, int $uid, ManagerRegistry $manager): Response
    {
        $appRepo = $manager->getRepository(Apprenti::class);
        $app = $appRepo->findOneBy(array("utilisateur" => $uid));
        return $this->json($evaluationRepository->findBy(array('apprenti' => $app)));
    }
}
