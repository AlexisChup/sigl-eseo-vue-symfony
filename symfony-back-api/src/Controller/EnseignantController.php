<?php

namespace App\Controller;

use App\classes\PasswordManager;
use App\Entity\Apprenti;
use App\Entity\CentreFormation;
use App\Entity\Enseignant;
use App\Entity\MaitreApprentissage;
use App\Entity\Utilisateur;
use App\Repository\EnseignantRepository;
use Couchbase\User;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use JsonException;
use OpenApi\Annotations as OAnn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\throwException;

#[Route('/api/enseignant', name: 'api_enseignant_')]
class EnseignantController extends AbstractController
{
    #[Route('/all', name: 'get_enseignant', methods: 'GET')]
    /**
     * @OAnn\Tag(name="Enseignant")
     */
    public function getEnseignants(EnseignantRepository $enseignantRepository): Response
    {
        return $this->json($enseignantRepository->findAll());
    }

    #[Route('/create', name: 'enseignant_create', methods: 'POST')]
    /**
     * @OAnn\Tag(name="Enseignant")
     * @throws JsonException
     */
    public function createEnseignant(EnseignantRepository $enseignantRepository, Request $request, ManagerRegistry $manager): Response
    {
        $centreRepo = $manager->getRepository(CentreFormation::class);
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $res = "ERROR";

        try {

            $enseignant = new Enseignant();
            $site = $centreRepo->find($data["site"]);
            $enseignant->setCentreFormation($site);

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

            $enseignant->setUtilisateur($utilisateur);
            $res = ['utilisateur' => $utilisateur, 'enseignant' => $enseignant];

            $enseignantRepository->add($enseignant, true);

        } catch (Exception) {
            throwException(new Exception("Erreur lors de la création d'un enseignant"));
        }

        return $this->json($res);
    }

    #[Route('/{id}/apprentis', name: 'app_enseignant_apprenti', methods: 'GET', requirements: ['id' => '\d+'])]
    /**
     * @OAnn\Tag(name="Enseignant")
     */
    public function getEnseignantApprenti(int $id, ManagerRegistry $doctrine): Response
    {
        $ensRepo = $doctrine->getRepository(Enseignant::class);
        $ens = $ensRepo->findOneBy(array('utilisateur' => $id));
        $appRepo = $doctrine->getRepository(Apprenti::class);
        return $this->json($appRepo->findBy(array('enseignant' => $ens->getId())));
    }
}
