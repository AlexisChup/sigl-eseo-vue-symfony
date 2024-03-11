<?php

namespace App\Controller;

use App\Repository\AdminRepository;
use OpenApi\Annotations as OAnn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/admin', name: 'api_admin_')]
class AdminController extends AbstractController
{
    #[Route('/admins', name: 'app_admin', methods: 'GET')]
    /**
     * @OAnn\Tag(name="Admin")
     */
    public function getAdmins(AdminRepository $adminRepository): Response
    {
	    return $this->json($adminRepository->findAll());
    }
}
