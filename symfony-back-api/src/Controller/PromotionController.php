<?php

namespace App\Controller;

use App\Repository\PromotionRepository;
use OpenApi\Annotations as OAnn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/promotion', name: 'api_promotion')]
class PromotionController extends AbstractController
{
    #[Route('/promotions', name: 'get_all_promotions', methods: 'GET')]
    /**
     * @OAnn\Tag(name="Promotion")
     */
    public function getPromotions(PromotionRepository $promotionRepository): Response
    {
	    return $this->json($promotionRepository->findAll());
    }
}
