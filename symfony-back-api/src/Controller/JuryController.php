<?php

namespace App\Controller;

use App\Entity\Apprenti;
use App\Entity\Enseignant;
use App\Entity\Event;
use App\Entity\Externe;
use App\Entity\Jury;
use App\Entity\MaitreApprentissage;
use App\Entity\Utilisateur;
use App\Repository\JuryRepository;
use OpenApi\Annotations as OAnn;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/jury', name: 'api_jury')]
class JuryController extends AbstractController
{
    #[Route('/juries', name: 'app_jury', methods: 'GET')]
    /**
     * @OAnn\Tag(name="Jury")
     */
    public function getJuries(JuryRepository $juryRepository): Response
    {
        return $this->json($juryRepository->findAll());
    }

    #[Route('/create', name: 'create_jury', methods: 'POST')]
    /**
     * @OAnn\Tag(name="Jury")
     */
    public function createJury(JuryRepository $juryRepository, ManagerRegistry $registry, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $evtRepo = $registry->getRepository(Event::class);
        $userRepo = $registry->getRepository(Utilisateur::class);
        $appRepo = $registry->getRepository(Apprenti::class);

        $juryExist = $juryRepository->findOneBy(array('evenement' => $data['eventId'], 'apprenti' => $data['appId']));

        if ($juryExist != null) {
            return $this->json('L\'apprenti a déjà un jury pour cet évènement.', 409);
        }

        $jury = new Jury();
        $jury->setEvenement($evtRepo->find($data['eventId']));
        $jury->setApprenti($appRepo->find($data['appId']));

        foreach ($data['audience'] as $auditeurId) {
            $jury->addAuditeur($userRepo->find($auditeurId));
        }

        $juryRepository->save($jury, true);

        return $this->json($jury);
    }

    #[Route('/{id}', name: 'get_jury_by_id', methods: 'GET', requirements: ['id' => '\d+'])]
    /**
     * @OAnn\Tag(name="Jury")
     */
    public function getJuriesById(JuryRepository $juryRepository, int $id): Response
    {
        return $this->json($juryRepository->find($id));
    }

    #[Route('/{id}', name: 'delete_jury_by_id', methods: 'DELETE', requirements: ['id' => '\d+'])]
    /**
     * @OAnn\Tag(name="Jury")
     */
    public function deleteJuriesById(JuryRepository $juryRepository, int $id): Response
    {
        $jury = $juryRepository->find($id);
        $juryRepository->remove($jury, true);
        return $this->json($jury);
    }

    #[Route('/{id}', name: 'update_jury_by_id', methods: 'PUT', requirements: ['id' => '\d+'])]
    /**
     * @OAnn\Tag(name="Jury")
     */
    public function updateJuryById(JuryRepository $juryRepository, ManagerRegistry $registry, int $id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $userRepo = $registry->getRepository(Utilisateur::class);

        $jury = $juryRepository->find($id);
        $jury->clearAudience();

        foreach ($data['audience'] as $auditeurId) {
            $jury->addAuditeur($userRepo->find($auditeurId));
        }

        $juryRepository->save($jury, true);

        return $this->json($jury);
    }

    #[Route('/possible-auditeurs', name: 'get_possible_auditeurs_by_id', methods: 'GET')]
    /**
     * @OAnn\Tag(name="Jury")
     */
    public function getPossibleAuditeur(JuryRepository $juryRepository, ManagerRegistry $registry): Response
    {
        $ensRepo = $registry->getRepository(Enseignant::class);
        $maRepo = $registry->getRepository(MaitreApprentissage::class);
        $extRepo = $registry->getRepository(Externe::class);

        $auditeurs = array();

        array_push($auditeurs, ...$ensRepo->findAll());
        array_push($auditeurs, ...$maRepo->findAll());
        array_push($auditeurs, ...$extRepo->findAll());

        return $this->json($auditeurs);
    }

    #[Route('/from-user/{id}', name: 'get_jury_from_user', methods: 'GET', requirements: ['id' => '\d+'])]
    /**
     * @OAnn\Tag(name="Jury")
     */
    public function getJuryFromUser(JuryRepository $juryRepository, ManagerRegistry $registry, int $id): Response
    {
        $userRepo = $registry->getRepository(Utilisateur::class);
        $user = $userRepo->find($id);

        $all_juries = $juryRepository->findAll();
        $user_juries = array();

        foreach ($all_juries as $jury) {
            $tmp_user = $jury->getAuditeur()->contains($user);
            if($tmp_user != null){
                array_push($user_juries, $jury);
            }
        }

        return $this->json($user_juries);
    }
}
