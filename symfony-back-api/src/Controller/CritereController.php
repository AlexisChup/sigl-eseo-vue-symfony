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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/critere', name: 'api_critere_')]
class CritereController extends AbstractController
{
    #[Route('/critere', name: 'app_critere', methods: 'GET')]
    /**
     * @OAnn\Tag(name="Critere")
     */
    public function getCriteres(CritereRepository $critereRepository): Response
    {
        return $this->json($critereRepository->findAll());
    }

    #[Route('/create', name: 'new_critere', methods: 'POST')]
    /**
     * @OAnn\Tag(name="Critere")
     */
    public function createCritere(ManagerRegistry $doctrine, CritereRepository $critereRepository, Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            if ($data !== null) {
                $critere = new Critere();
                $critere->setContenu($data['contenu'] ?? null);

                $grilleRepository = $doctrine->getRepository(Grille::class);
                $grille = $grilleRepository->findOneBy(['id' => $data['grille']['id'] ?? null]);
                $critere->setGrille($grille ?? null);

                $parent = $critereRepository->findOneBy(['id' => $data['parent']['id'] ?? null]);
                $critere->setParent($parent ?? null);

                $critereRepository->add($critere, true);

                return $this->json($critere);
            }
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
        return $this->json(['error' => 'missing data'], 422);
    }

    #[Route('/update/{id}', name: 'update_critere', requirements: ['id' => '\d+'], methods: 'PUT')]
    /**
     * @OAnn\Tag(name="Critere")
     */
    public function updateCritere(int $id, ManagerRegistry $doctrine, CritereRepository $critereRepository, Request $request): Response
    {
        try {
            $critere = $critereRepository->findOneBy(['id' => $id]);
            if ($critere !== null) {
                $data = json_decode($request->getContent(), true);

                $grilleRepository = $doctrine->getRepository(Grille::class);
                $grille = $grilleRepository->findOneBy(['id' => $data['grille']['id'] ?? null]);
                $critere->setGrille($grille ?? null);

                $parent = $critereRepository->findOneBy(['id' => $data['parent']['id'] ?? null]);
                $critere->setParent($parent ?? null);

                $critere->setContenu($data['contenu'] ?? null);
                $critere->setCoefficient($data['coefficient'] ?? null);

                $em = $doctrine->getManager();
                $em->persist($critere);
                $em->flush();

                return $this->json($critere);
            }
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
        return $this->json(['error' => 'missing data'], 422);
    }

    #[Route('/remove/{id}', name: 'remove_categorie', requirements: ['id' => '\d+'], methods: 'DELETE')]
    /**
     * @OAnn\Tag(name="Grille")
     */
    public function removeGrille(int $id,CritereRepository $critereRepository, Request $request): Response
    {
        try {
            $critere = $critereRepository->findOneBy(['id' => $id]);
            if ($critere !== null) {

                // ON CASCADE MANUEL
                $criteres = $critereRepository->findBy(['parent' => $id]);
                foreach ($criteres as $enfant){
                    $critereRepository->remove($enfant,true);
                }
                // PARCE QUE LE ON CASCADE CA NE MARCHE PAS

                $critereRepository->remove($critere, true);

                return $this->json([], 204);
            }
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
        return $this->json(['error' => 'missing data'], 422);
    }
}
