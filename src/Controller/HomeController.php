<?php

namespace App\Controller;

use App\Repository\MeubleRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(MeubleRepository $meubleRepo, CategorieRepository $categorieRepo): Response
    {
        return $this->render('home/index.html.twig', [
            'meubles'    => $meubleRepo->findBy([], ['id' => 'DESC'], 8),
            'categories' => $categorieRepo->findAll(),
        ]);
    }
}