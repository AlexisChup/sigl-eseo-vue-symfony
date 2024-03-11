<?php

namespace App\Controller;

use App\Entity\Critere;
use App\Entity\Grille;
use App\Repository\CritereRepository;
use App\Repository\GrilleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use OpenApi\Annotations as OAnn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/grille', name: 'api_grille_')]
class GrilleController extends AbstractController
{
    #[Route('/grille', name: 'app_grille', methods: 'GET')]
    /**
     * @OAnn\Tag(name="Grille")
     */
    public function getGrilles(GrilleRepository $grilleRepository): Response
    {
        return $this->json($grilleRepository->findAll());
    }

    #[Route('/grille/criteres', name: 'grille_criteres', methods: 'GET')]
    /**
     * @OAnn\Tag(name="Grille")
     */
    public function getGrillesAvecCriteres(GrilleRepository $grilleRepository, CritereRepository $critereRepository): Response
    {
        $grilles = [];
        foreach ($grilleRepository->findBy(['event' => null]) as $grille) {
            $categories = [];
            foreach ($critereRepository->findBy(['parent' => null, 'grille' => $grille]) as $categorie) {
                $criteres = $critereRepository->findBy(['parent' => $categorie, 'grille' => $grille]);

                $categorie_struct['categorie'] = $categorie;
                $categorie_struct['criteres'] = $criteres;
                $categories[] = $categorie_struct;
            }
            $grille_struct['grille'] = $grille;
            $grille_struct['categories'] = $categories;
            $grilles[] = $grille_struct;
        }
        return $this->json($grilles);
    }

    #[Route('/create', name: 'new_grille', methods: 'POST')]
    /**
     * @OAnn\Tag(name="NewGrille")
     */
    public function createGrille(GrilleRepository $grilleRepository, Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            if ($data !== null) {
                $grille = new Grille();
                $grille->setNom($data['nom']);
                $grilleRepository->add($grille, true);

                return $this->json($grille);
            }
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
        return $this->json(['error' => 'missing data'], 422);
    }

    #[Route('/remove/{id}', name: 'remove_grille', requirements: ['id' => '\d+'], methods: 'DELETE')]
    /**
     * @OAnn\Tag(name="Grille")
     */
    public function removeGrille(int $id,CritereRepository $critereRepository, GrilleRepository $grilleRepository, Request $request): Response
    {
        try {
            $grille = $grilleRepository->findOneBy(['id' => $id]);
            if ($grille !== null) {

                // ON CASCADE MANUEL
                $criteres = $critereRepository->findBy(['grille' => $id], ['parent' => 'ASC']);
                foreach ($criteres as $critere){
                    $critereRepository->remove($critere,true);
                }
                // PARCE QUE LE ON CASCADE CA NE MARCHE PAS

                $grilleRepository->remove($grille, true);

                return $this->json([], 204);
            }
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
        return $this->json(['error' => 'missing data'], 422);
    }
}
