<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MeubleController extends AbstractController
{
    #[Route('/meuble', name: 'app_meuble')]
    public function index(): Response
    {
        return $this->render('meuble/index.html.twig', [
            'controller_name' => 'MeubleController',
        ]);
    }
}
