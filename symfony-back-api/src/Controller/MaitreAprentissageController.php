<?php

namespace App\Controller;

use App\classes\PasswordManager;
use App\Entity\Apprenti;
use App\Entity\Entreprise;
use App\Entity\MaitreApprentissage;
use App\Entity\Utilisateur;
use App\Repository\MaitreApprentissageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use JsonException;
use OpenApi\Annotations as OAnn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\throwException;

#[Route('/api/maitre-aprentissage', name: 'maitre_aprentissage')]
class MaitreAprentissageController extends AbstractController
{
    #[Route('/all', name: 'get_maitre_aprentissage', methods: 'GET')]
    /**
     * @OAnn\Tag(name="MaitreApprentissage")
     */
    public function getMaitresApprentissage(MaitreApprentissageRepository $maitreApprentissageRepository): Response
    {
        return $this->json($maitreApprentissageRepository->findAll());
    }

    #[Route('/{id}', name: 'get_maitre_aprentissage_id', methods: 'GET')]
    /**
     * @OAnn\Tag(name="MaitreApprentissage")
     */
    public function getMaitreApprentissageById(MaitreApprentissageRepository $maitreApprentissageRepository, int $id): Response
    {
        return $this->json($maitreApprentissageRepository->find($id));
    }

    #[Route('/create', name: 'create_ma', methods: 'POST')]
    /**
     * @OAnn\Tag(name="MaitreApprentissage")
     * @throws JsonException
     */
    public function createMA(MaitreApprentissageRepository $maitreApprentissageRepository, Request $request, ManagerRegistry $manager): Response
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $entrepriseRepo = $manager->getRepository(Entreprise::class);

        try {
            $ma = new MaitreApprentissage();
            $entreprise = $entrepriseRepo->find($data["entreprise"]);
            $ma->setEntreprise($entreprise);

            $utilisateur = new Utilisateur();
            $utilisateur->setNom($data["nom"]);
            $utilisateur->setPrenom($data["prenom"]);
            $utilisateur->setTelephone($data["tel"]);
            $utilisateur->setAdresse($data["adresse"]);
            $utilisateur->setEmail($data["email"]);
            $utilisateur->setActif(true);

            $passManager = new PasswordManager();
            $pass = $passManager->generateRandomPass();
            $utilisateur->setMotDePasse($pass);

            $ma->setUtilisateur($utilisateur);
            $maitreApprentissageRepository->add($ma, true);
            $res = ["utilisateur" => $utilisateur, "ma" => $ma, "entreprise" => $entreprise];
        } catch (Exception) {
            $res = ["ERROR" => "Erreur lors de la création du maître d'apprentissage"];
        }
        return $this->json($res);
    }

    #[Route('/{id_user}/apprentis', name: 'app_maitre_aprentissage', requirements: ['id' => '\d+'], methods: 'GET')]
    /**
     * @OAnn\Tag(name="MaitreApprentissage")
     */
    public function getApprentisMA(ManagerRegistry $doctrine, int $id_user): Response
    {
        $appRepo = $doctrine->getRepository(Apprenti::class);
        $maRepo = $doctrine->getRepository(MaitreApprentissage::class);

        $ma = $maRepo->findOneBy(array('utilisateur' => $id_user));

        if ($ma !== null) {
            $data = $appRepo->getApprentisMA($ma->getId());
            return $this->json($data);
        }
        return $this->json([], 400);
    }
}
