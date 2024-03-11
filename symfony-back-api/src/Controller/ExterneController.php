<?php

namespace App\Controller;

use App\Repository\ExterneRepository;
use OpenApi\Annotations as OAnn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/externe', name: 'api_externe_')]
class ExterneController extends AbstractController
{
    #[Route('/externe', name: 'app_externe', methods: 'GET')]
    /**
     * @OAnn\Tag(name="Externe")
     */
    public function getExternes(ExterneRepository $externeRepository): Response
    {
	    return $this->json($externeRepository->findAll());
    }
}
