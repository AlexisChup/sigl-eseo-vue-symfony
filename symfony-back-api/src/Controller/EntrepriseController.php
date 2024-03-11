<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Repository\EntrepriseRepository;
use Exception;
use OpenApi\Annotations as OAnn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/entreprise', name: 'api_entreprise_')]
class EntrepriseController extends AbstractController
{
    #[Route('/entreprises', name: 'get_all_entreprises', methods: 'GET')]
    /**
     * @OAnn\Tag(name="Entreprise")
     */
    public function getAllEntreprises(EntrepriseRepository $entrepriseRepository): Response
    {
	    return $this->json($entrepriseRepository->findAll());
    }

    #[Route('/get/{id}', name: 'get_entreprise_by_id', methods: 'GET')]
    /**
     * @OAnn\Tag(name="Entreprise")
     */
    public function getEntrepriseById(int $id, EntrepriseRepository $entrepriseRepository): Response
    {
        $dataEntreprise = $entrepriseRepository->find($id);
        if (!$dataEntreprise) {
            return $this->json(["ERROR" => "ERREUR : Impossible de récupérer les informations de l'entreprise"]);
        }
        return $this->json($dataEntreprise);
    }

    #[Route('/remove/{id}', name: 'remove_entreprise_by_id', methods: 'DELETE')]
    /**
     * @OAnn\Tag(name="Entreprise")
     */
    public function removeEntrepriseById(int $id, EntrepriseRepository $entrepriseRepository): Response
    {
        $entreprise = $entrepriseRepository->find($id);
        if ($entreprise !== null) {
            $entrepriseRepository->remove($entreprise, true);
            return $this->json([], 204);
        }
        return $this->json([], 400);
    }

    #[Route('/edit/{id}', name: 'edit_entreprise_by_id', methods: 'PUT')]
    /**
     * @OAnn\Tag(name="Entreprise")
     * @throws \JsonException
     */
    public function editEntrepriseById(int $id, EntrepriseRepository $entrepriseRepository, Request $http): Response
    {
        $data = json_decode($http->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $entreprise = $entrepriseRepository->find($id);

        if ($entreprise !== null) {
            $entreprise->setNom($data["nom"]);
            $entreprise->setAdresse($data["adresse"]);
            $entreprise->setVille($data["ville"]);
            $entreprise->setCodePostal($data["codePostal"]);
            $entreprise->setSiret($data["siret"]);

            $entrepriseRepository->updateEntreprise($entreprise);
            return $this->json($entreprise);
        }
        return $this->json([], 400);
    }

    #[Route('/create', name: 'create_entreprise', methods: ["POST"])]
    /**
     * @OAnn\Tag(name="Entreprise")
     */
    public function createEntreprise(EntrepriseRepository $entrepriseRepository, Request $http): Response
    {
        $entreprise = new Entreprise();

        try {
            $data = json_decode($http->getContent(), true, 512, JSON_THROW_ON_ERROR);

            $entreprise->setNom($data["nom"]);
            $entreprise->setAdresse($data["adresse"]);
            $entreprise->setVille($data["ville"]);
            $entreprise->setCodePostal($data["codePostal"]);
            $entreprise->setSiret($data["siret"]);

            $entrepriseRepository->add($entreprise, true);

            $res = $entreprise;
        } catch (Exception) {
            $res = [];
        }
        return $this->json($res);
    }
}
