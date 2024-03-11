<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Apprenti;
use App\Entity\Coordinateur;
use App\Entity\Enseignant;
use App\Entity\MaitreApprentissage;
use App\Entity\Utilisateur;
use Exception;
use JsonException;
use OpenApi\Annotations as OAnn;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/utilisateur', name: 'api_utilisateur')]
class UtilisateurController extends AbstractController
{
    #[Route('/all', name: 'get_all_utilisateurs', methods: 'GET')]
    /**
     * @OAnn\Tag(name="Utilisateur")
     */
    public function getUtilisateurs(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->json($utilisateurRepository->findAll());
    }

    #[Route('/create', name: 'create_utilisateur', methods: 'POST')]
    /**
     * @OAnn\Tag(name="Utilisateur")
     */
    public function createUtilisateur(UtilisateurRepository $utilisateurRepository, Request $request): Response
    {
        $utilisateur = new Utilisateur();

        try {
            $data = json_decode($request->getContent(), true);

            $utilisateur->setNom($data["nom"]);
            $utilisateur->setPrenom($data["prenom"]);
            $utilisateur->setMotDePasse($data["motDePasse"]);
            $utilisateur->setTelephone($data["tel"]);
            $utilisateur->setAdresse($data["adresse"]);
            $utilisateur->setEmail($data["email"]);
            $utilisateur->setActif($data["actif"]);

            $utilisateurRepository->add($utilisateur, true);
            $res = $utilisateur;
        } catch (Exception) {
            $res = [];
        }

        return $this->json($res);
    }


    #[Route('/apprentis', name: 'get_all_apprentis', methods: 'GET')]
    /**
     * @OAnn\Tag(name="Utilisateur")
     */
    public function getAllApprentis(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->json($utilisateurRepository->getUtilisateurApprentis());
    }


    #[Route('/enseignants', name: 'get_all_enseignants', methods: 'GET')]
    /**
     * @OAnn\Tag(name="Utilisateur")
     */
    public function getAllEnseignants(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->json($utilisateurRepository->getUtilisateurEnseignants());
    }

    #[Route('/connexion', name: 'connexion', methods: 'POST')]
    /**
     * @OAnn\Tag(name="Utilisateur")
     * @throws JsonException
     */
    public function connexion(UtilisateurRepository $utilisateurRepository, Request $request, ManagerRegistry $doctrine): Response
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $username = $data["username"];
        $password = $data["password"];

        $utilisateurs = $utilisateurRepository->connexion($username, $password);

        if (count($utilisateurs) === 1) {
            $utilisateur = $utilisateurs[0];
            $perm = json_decode($this->getRoleFromUtilisateur($utilisateur->getId(), $doctrine)->getContent());
            return $this->json(array('user' => $utilisateur, 'perm' => $perm->role));
        }

        return $this->json(array('user' => null, 'perm' => 'UNIDENTIFIED', 'error' => 'CONNEXION FAILED.'));
    }

    #[Route('/update/{id}', name: 'update_utilisateur_complet', requirements: ['id' => '\d+'] ,methods: 'PUT')]
    /**
     * @OAnn\Tag(name="Utilisateur")
     */
    public function updateUtilisateur(int $id, UtilisateurRepository $utilisateurRepository, Request $request): Response
    {
        $utilisateur = $utilisateurRepository->findOneBy(array("id" => $id));

        try {
            $data = json_decode($request->getContent(), true);

            $utilisateur->setNom($data["nom"]);
            $utilisateur->setPrenom($data["prenom"]);
            $utilisateur->setMotDePasse($data["motDePasse"]);
            $utilisateur->setTelephone($data["tel"]);
            $utilisateur->setAdresse($data["adresse"]);
            $utilisateur->setEmail($data["email"]);
            $utilisateur->setActif($data["actif"]);

            $utilisateurRepository->add($utilisateur, true);
            $res = "ok";
        } catch (e) {
            $res = "Utilisateur not create";
        }

        return $this->json($res);
    }

    #[Route('/{id}/update_contact', name: 'update_contact_utilisateur', methods: 'PUT')]
    /**
     * @OAnn\Tag(name="Utilisateur")
     */
    public function updateUtilisateurContact(int $id, UtilisateurRepository $utilisateurRepository, Request $request): Response
    {
        $utilisateur = $utilisateurRepository->findOneBy(array("id" => $id));
        try {
            $data = json_decode($request->getContent(), true);

            $utilisateur->setEmail($data["email"]);
            $utilisateur->setTelephone($data["tel"]);
            $utilisateur->setAdresse($data["adresse"]);

            $res = $utilisateurRepository->updateUtilisateur($utilisateur);
        } catch (e) {
            $res = "Utilisateur not update";
        }

        return $this->json($res);
    }

    #[Route('/{id}/update_pwd', name: 'update_pwd_utilisateur', requirements: ['id' => '\d+'] ,methods: 'PUT')]
    /**
     * @OAnn\Tag(name="Utilisateur")
     */
    public function updateUtilisateurPassword(int $id, UtilisateurRepository $utilisateurRepository, Request $request): Response
    {
        $utilisateur = $utilisateurRepository->findOneBy(array("id" => $id));
        try {
            $data = json_decode($request->getContent(), true);
            if ($data['pwd'] !== 'd41d8cd98f00b204e9800998ecf8427e') {
                $utilisateur->setMotDePasse($data["pwd"]);
                $res = $utilisateurRepository->updateUtilisateur($utilisateur);
            }
            else
            {
                $res = $utilisateurRepository->updateUtilisateur($utilisateur);
            }

        } catch (Exception) {
            return $this->json(["ERREOR" => "Erreur lors de la modification du mot de passe"]);
        }
        return $this->json($res);
    }


    private function getUtilisateurRightString(int $id, ManagerRegistry $doctrine): array
    {
        $adminRepo = $doctrine->getRepository(Admin::class);
        $appRepo = $doctrine->getRepository(Apprenti::class);
        $coordRepo = $doctrine->getRepository(Coordinateur::class);
        $ensRepo = $doctrine->getRepository(Enseignant::class);
        $maRepo = $doctrine->getRepository(MaitreApprentissage::class);

        $perm = array();

        if (!empty($adminRepo->getUtilisateur($id))) {
            $perm = array('perm' => 'ADMIN');
        } elseif (!empty($appRepo->getUtilisateur($id))) {
            $perm = array('perm' => 'APPRENTI');
        } elseif (!empty($coordRepo->getUtilisateur($id))) {
            $perm = array('perm' => 'COORDINATEUR');
        } elseif (!empty($ensRepo->getUtilisateur($id))) {
            $perm = array('perm' => 'ENSEIGNANT');
        } elseif (!empty($maRepo->getUtilisateur($id))) {
            $perm = array('perm' => 'MAITRE-APPRENTISSAGE');
        } else {
            $perm = array('perm' => 'UNIDENTIFIED');
        }

        return $perm;
    }

	#[Route('/{id}/rights', name: 'Utilisateur_right', requirements: ['id' => '\d+'], methods: 'GET')]
    public function getRights(int $id, ManagerRegistry $doctrine): Response
    {
        return $this->getRoleFromUtilisateur($id, $doctrine);
    }

    #[Route('/{id}', name: 'get_utilisateur', requirements: ['id' => '\d+'], methods: 'GET')]
    /**
     * @OAnn\Tag(name="Utilisateur")
     */
    public function getUtilisateurById(int $id, UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->json($utilisateurRepository->findOneBy(array("id" => $id)));
    }

    #[Route('/{id}/role', name: 'get_utilisateur_role', requirements: ['id' => '\d+'], methods: 'GET')]
    /**
     * @OAnn\Tag(name="Utilisateur")
     */
    public function getRoleFromUtilisateur(int $id, ManagerRegistry $doctrine): Response
    {

        $adminRepo = $doctrine->getRepository(Admin::class);
        $appRepo = $doctrine->getRepository(Apprenti::class);
        $coordRepo = $doctrine->getRepository(Coordinateur::class);
        $ensRepo = $doctrine->getRepository(Enseignant::class);
        $maRepo = $doctrine->getRepository(MaitreApprentissage::class);

        $response = null;

        if (!empty($adminRepo->getUtilisateur($id))) {
            $response['role'] = 'ADMIN';
            $response['data'] = $adminRepo->getUtilisateur($id);
        } elseif (!empty($appRepo->getUtilisateur($id))) {
            $response['role'] = 'APPRENTI';
            $response['data'] = $appRepo->getUtilisateur($id);
        } elseif (!empty($coordRepo->getUtilisateur($id))) {
            $response['role'] = 'COORDNIATEUR';
            $response['data'] = $coordRepo->getUtilisateur($id);
        } elseif (!empty($ensRepo->getUtilisateur($id))) {
            $response['role'] = 'ENSEIGNANT';
            $response['data'] = $ensRepo->getUtilisateur($id);
        } elseif (!empty($maRepo->getUtilisateur($id))) {
            $response['role'] = 'MAITRE-APPRENTISSAGE';
            $response['data'] = $maRepo->getUtilisateur($id);
        } else {
            $response['role'] = 'UNIDENTIFIED';
            $response['data'] = null;
        }

        return $this->json($response);
    }

    #[Route('/remove/{id}', name: 'remove_utilisateur_by_id', requirements: ['id' => '\d+'] ,methods: 'DELETE')]
    /**
     * @OAnn\Tag(name="Utilisateur")
     */
    public function removeUserById(int $id, UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateurRepository->removeById($id);
        return $this->json("OK");
    }
}
