<?php

namespace App\Controller;

use App\Repository\CentreFormationRepository;
use OpenApi\Annotations as OAnn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/centre-formation', name: 'api_centre_formation_')]
class CentreFormationController extends AbstractController
{
    #[Route('/centres', name: 'app_centre_formation', methods: 'GET')]
    /**
     * @OAnn\Tag(name="CentreFormation")
     */
    public function getCentresFormation(CentreFormationRepository $centreFormationRepository): Response
    {
	    return $this->json($centreFormationRepository->findAll());
    }
}
