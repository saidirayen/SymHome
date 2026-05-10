<?php

namespace App\Controller;

use App\Repository\MeubleRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MeubleController extends AbstractController
{
    #[Route('/meubles', name: 'app_meuble')]
    public function index(Request $request, MeubleRepository $meubleRepo, CategorieRepository $categorieRepo): Response
    {
        $search = $request->query->get('search', '');
        $categorieId = $request->query->get('categorie') ? (int) $request->query->get('categorie') : null;

        $meubles = $meubleRepo->search($search, $categorieId);
        $categories = $categorieRepo->findAll();

        return $this->render('meuble/index.html.twig', [
            'meubles' => $meubles,
            'categories' => $categories,
            'search' => $search,
            'categorieId' => $categorieId,
        ]);
    }

    #[Route('/meubles/{id}', name: 'app_meuble_show', requirements: ['id' => '\d+'])]
    public function show(int $id, MeubleRepository $meubleRepo): Response
    {
        $meuble = $meubleRepo->find($id);

        if (!$meuble) {
            throw $this->createNotFoundException('Meuble introuvable.');
        }

        return $this->render('meuble/show.html.twig', [
            'meuble' => $meuble,
        ]);
    }
}