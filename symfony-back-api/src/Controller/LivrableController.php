<?php

namespace App\Controller;


use App\Entity\Livrable;
use App\Repository\LivrableRepository;
use OpenApi\Annotations as OAnn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/livrable', name: 'api_livrable')]
class LivrableController extends AbstractController
{
    #[Route('/livrables', name: 'app_livrable', methods: 'GET')]
    /**
     * @OAnn\Tag(name="Livrable")
     */
    public function getLivrables(LivrableRepository $livrableRepository): Response
    {
	    return $this->json($livrableRepository->findAll());
    }

    #[Route('/edit/{id}', name: 'edit_livrable_by_id', methods: 'PUT')]
    /**
     * @OAnn\Tag(name="Livrable")
     */
    public function editLivrableById(int $id, LivrableRepository $livrableRepository, Request $http): Response
    {
        $data = json_decode($http->getContent(), true);

        $livrable = $livrableRepository->find($id);

        $livrable->setEvent($data["event_id"]);

        $res = $livrableRepository->updateEntreprise($livrable);

        return $this->json($data);
    }


    #[Route('/create', name: 'create_livrable', methods: ["POST"])]
    /**
     * @OAnn\Tag(name="Livrable")
     */
    public function createLivrable(LivrableRepository $livrableRepository, Request $http): Response
    {
        $livrable = new Livrable();

        try {
            $data = json_decode($http->getContent(), true);

            $livrable->setEvent($data["event_id"]);

            $livrableRepository->add($livrable, true);

            $res="ok";
        } catch (e) {
            $res="livrable-non-ajoutee";
        }

        return $this->json($res);
    }



    #[Route('/remove/{id}', name: 'remove_livrable_by_id', methods: 'DELETE')]
    /**
     * @OAnn\Tag(name="Livrable")
     */
    public function removeLivrableById(int $id, LivrableRepository $livrableRepository): Response
    {
        $livrableRepository->removeById($id);
        return $this->json("OK");
    }


    #[Route('/get/{id}', name: 'get_livrable_by_id', methods: 'GET')]
    /**
     * @OAnn\Tag(name="Livrable")
     */
    public function getLivrableById(int $id, LivrableRepository $livrableRepository): Response
    {
        return $this->json($livrableRepository->find($id));
    }


}
