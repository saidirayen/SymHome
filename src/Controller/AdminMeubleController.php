<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminMeubleController extends AbstractController
{
    #[Route('/admin/meuble', name: 'app_admin_meuble')]
    public function index(): Response
    {
        return $this->render('admin_meuble/index.html.twig', [
            'controller_name' => 'AdminMeubleController',
        ]);
    }
}
