<?php

namespace App\Controller;

use App\classes\PasswordManager;
use App\Entity\Coordinateur;
use App\Entity\Utilisateur;
use App\Repository\CoordinateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use JsonException;
use OpenApi\Annotations as OAnn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\throwException;

#[Route('/api/coordinateur', name: 'api_coordinateur_')]
class CoordinateurController extends AbstractController
{
    #[Route('/coordinateurs', name: 'get_coordinateur', methods: 'GET')]
    /**
     * @OAnn\Tag(name="Coordinateur")
     */
    public function getCoordinateurs(CoordinateurRepository $coordinateurRepository): Response
    {
        return $this->json($coordinateurRepository->findAll());
    }

    #[Route('/create', name: 'app_coordinateur', methods: 'POST')]
    /**
     * @OAnn\Tag(name="Coordinateur")
     * @throws JsonException
     */
    public function createCoordinateurs(CoordinateurRepository $coordinateurRepository, Request $request, ManagerRegistry $manager): Response
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        try {
            $coord = new Coordinateur();

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

            $coord->setUtilisateur($utilisateur);
            $coordinateurRepository->add($coord, true);

            $res = ["utilisateur" => $utilisateur, "coordinateur" => $coord];
        } catch (Exception) {
            $res = ["ERROR" => "Erreur lors de la création d'un coordinateur"];
        }

        return $this->json($res);
    }

}
