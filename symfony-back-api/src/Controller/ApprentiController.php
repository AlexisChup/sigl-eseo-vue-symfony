<?php

namespace App\Controller;

use App\classes\PasswordManager;
use App\Entity\Apprenti;
use App\Entity\Promotion;
use App\Entity\Utilisateur;
use App\Repository\ApprentiRepository;
use App\Repository\EnseignantRepository;
use App\Repository\MaitreApprentissageRepository;
use App\Repository\PromotionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use JsonException;
use OpenApi\Annotations as OAnn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\throwException;

#[Route('/api/apprenti', name: 'api_apprenti_')]
class ApprentiController extends AbstractController
{
    #[Route('/{id}', name: 'get_apprenti', requirements: ['id' => '\d+'], methods: 'GET')]
    /**
     * @OAnn\Tag(name="Apprenti")
     */
    public function getInfosApprenti(int $id, ApprentiRepository $apprentiRepository): Response
    {
        $dataApprenti = $apprentiRepository->find($id);
        if (!$dataApprenti) {
            $res = $this->json(["ERROR" => "ERREUR : Impossible de récupérer les informations personnelles"]);
        } else {
            $res = $this->json($dataApprenti);
        }
        return $res;
    }

    #[Route('/user/{id}', name: 'get_apprenti_user_id', requirements: ['id' => '\d+'], methods: 'GET')]
    /**
     * @OAnn\Tag(name="Apprenti")
     */
    public function getApprentiByUserId(int $id, ApprentiRepository $apprentiRepository): Response
    {
        $apprenti = $apprentiRepository->findOneBy(array('utilisateur' => $id));
        if ($apprenti === null) {
            $res = ["ERROR" => "ERREUR : impossible de récupérer les informations de l'apprenti"];
        } else {
            $res = $apprenti;
        }
        return $this->json($res);
    }

    #[Route('/apprenti', name: 'app_apprenti', methods: 'GET')]
    /**
     * @OAnn\Tag(name="Apprenti")
     */
    public function getApprentis(ApprentiRepository $apprentiRepository): Response
    {
        return $this->json($apprentiRepository->findAll());
    }

    #[Route('/create', name: 'create_apprenti', methods: 'POST')]
    /**
     * @OAnn\Tag(name="Apprenti")
     * @throws JsonException
     */
    public function createApprenti(ApprentiRepository $apprentiRepository, Request $request, ManagerRegistry $manager): Response
    {
        $promoRepo = $manager->getRepository(Promotion::class);
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $res = "ERROR";

        try {
            $apprenti = new Apprenti();

            $promo = $promoRepo->find($data["promotion"]);
            $apprenti->setIdPromotion($promo);

            $utilisateur = new Utilisateur();
            $utilisateur->setNom($data["nom"]);
            $utilisateur->setPrenom($data["prenom"]);
            $utilisateur->setTelephone($data["tel"]);
            $utilisateur->setAdresse($data["adresse"]);
            $utilisateur->setEmail($data["email"]);
            $utilisateur->setActif(true);

            $passManager = new PasswordManager();
            $pass = $passManager->generateRandomPass();
            $hash = $passManager->hashPassword($pass);

            if ($passManager->verifyPass($pass, $hash)) {
                $utilisateur->setMotDePasse($hash);
            } else {
                throwException(new Exception("Password and hash do not match"));
            }

            $apprenti->setUtilisateur($utilisateur);
            $res = ['apprenti' => $apprenti, 'utilisateur' => $utilisateur ];

            $apprentiRepository->add($apprenti, true);

        } catch (Exception) {
            throwException(new Exception("Erreur lors de la création d'un apprenti"));
        }

        return $this->json($res);
    }

    #[Route('/update/{id}', name: 'update_apprenti', requirements: ['id' => '\d+'] ,methods: 'POST')]
    public function updateApprenti (int $id, ApprentiRepository $apprentiRepository, PromotionRepository $promotionRepository,EnseignantRepository $enseignantRepository, MaitreApprentissageRepository $maitreApprentissageRepository, Request $request) : Response
    {
        $app = $apprentiRepository->findOneBy(array("id" => $id));

        $data = json_decode($request->getContent(), true);
        try {
            $promotion = $promotionRepository->findOneBy(array("id" => $data["id_promotion_id"]));
            $enseignant = $enseignantRepository->findOneBy(array("id" => $data["id_enseignant_id"]));
            $maitre = $maitreApprentissageRepository->findOneBy(array("id" => $data["id_ma_id"]));
            $app->setMission($data["mission"]);
            $app->setMissionValide($data["missionValide"]);
            $app->setEnseignant($enseignant);
            $app->setMaitreApprentissage($maitre);
            $app->setIdPromotion($promotion);

            $apprentiRepository->add($app, true);
            $res = "OK";
        } catch (Exception) {
            throwException(new Exception("Erreur lors de la création d'un apprenti"));
        }

        return $this->json($res);
    }

    /**
     * @throws JsonException
     */
    #[Route('/update/EquipeTutorale/{id}', name: 'update_EquipeTutorale', requirements: ['id' => '\d+'] ,methods: 'POST')]
    public function updateEquipetutorale (int $id, ApprentiRepository $apprentiRepository,EnseignantRepository $enseignantRepository, MaitreApprentissageRepository $maitreApprentissageRepository, Request $request) : Response
    {
        $app = $apprentiRepository->findOneBy(array("id" => $id));

        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        try {
            $enseignant = $enseignantRepository->findOneBy(array("utilisateur" => $data["id_enseignant_id"]));
            $maitre = $maitreApprentissageRepository->findOneBy(array("utilisateur" => $data["id_ma_id"]));

            $app->setEnseignant($enseignant);
            $app->setMaitreApprentissage($maitre);

            $apprentiRepository->add($app, true);
            $res = "OK";
        } catch (e) {
            $res = "user not create";
        }

        return $this->json($res);
    }

    #[Route('/update/mission/{id}', name: 'update_mission', requirements: ['id' => '\d+'] ,methods: 'PUT')]
    public function updateMission(int $id, ApprentiRepository $apprentiRepository, Request $request) : Response
    {
        $app = $apprentiRepository->find($id);

        $data = json_decode($request->getContent(), true);
        $data_missions = $data['missions'];

        if ($app !== null) {
            $app->setMission($data_missions['contenu_mission']);
            $app->setMissionValide(filter_var($data_missions['valide'], FILTER_VALIDATE_BOOLEAN));

            $apprentiRepository->add($app, true);
            return $this->json($app);
        }
        return $this->json(["ERROR" => "Erreur lors de la modification des missions"]);
    }

    #[Route('/equipe', name: 'equipe_apprenti', methods: 'POST')]
    /**
     * @OAnn\Tag(name="Apprenti")
     * @throws JsonException
     */
    public function getEquipeApprenti(ApprentiRepository $apprentiRepository, int $idPromo, Request $request, ManagerRegistry $manager): Response
    {

        $promoRepo = $manager->getRepository(Promotion::class);
        $ensRepo = $manager->getRepository(Enseignant::class);
        $maRepo = $manager->getRepository(MaitreApprentissage::class);
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $res = "ERROR";

        try {

            $apprenti = new Apprenti();

            $promo = $promoRepo->findOneBy(array('nom' => $data['promotion']));
            $apprenti->getIdPromotion($promo);

            $user = new User();
            $user->getNom();
            $user->getPrenom();

            $apprenti->getIdUser();
            $apprenti->getIdEnseignant();
            $apprenti->getIdMA();

            $res = $apprenti;

        }catch(e){

        }
        return $this->json($res);
    }

    #[Route('/promotion/{id}', name: 'apprentis_from_promo', requirements: ['id' => '\d+'], methods: 'GET')]
    /**
     * @OAnn\Tag(name="Apprenti")
     */
    public function getAppFromPromo(int $id, ApprentiRepository $apprentiRepository): Response
    {
        return $this->json($apprentiRepository->findBy(array('idPromotion' => $id)));
    }

    #[Route('/equipe/{id}', name: 'equipe_infos', requirements: ['id' => '\d+'], methods: 'GET')]
    /**
     * @OAnn\Tag(name="Apprenti")
     */
    public function getEquipeInfo(ApprentiRepository $apprentiRepository, int $id, Request $request, ManagerRegistry $manager): Response
    {
        $appRep = $apprentiRepository->findOneBy(array("id" => $id));
        if(!$appRep) {
            $res = $this->json("ERREUR : Impossible de récupérer les informations personnelles");
        }else{
            $res = $this->json($appRep);
        }
        return $res;
    }
}


