<?php

namespace App\Controller;

use App\Repository\NiveauRepository;
use OpenApi\Annotations as OAnn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/niveau', name: 'api_niveau')]
class NiveauController extends AbstractController
{
    #[Route('/niveaux', name: 'app_niveau', methods: 'GET')]
    /**
     * @OAnn\Tag(name="Niveau")
     */
    public function getNiveaux(NiveauRepository $niveauRepository): Response
    {
	    return $this->json($niveauRepository->findAll());
    }
}
