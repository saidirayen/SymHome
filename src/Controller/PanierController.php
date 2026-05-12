<?php

namespace App\Controller;

use App\Repository\MeubleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(Request $request, MeubleRepository $meubleRepo): Response
    {
        $panier = $request->getSession()->get('panier', []);
        $items = [];
        $total = 0;

        foreach ($panier as $id => $quantite) {
            $meuble = $meubleRepo->find($id);
            if ($meuble) {
                $sousTotal = $meuble->getPrix() * $quantite;
                $total += $sousTotal;
                $items[] = [
                    'meuble' => $meuble,
                    'quantite' => $quantite,
                    'sousTotal' => $sousTotal,
                ];
            }
        }

        return $this->render('panier/index.html.twig', [
            'items' => $items,
            'total' => $total,
        ]);
    }

    #[Route('/panier/add/{id}', name: 'app_panier_add')]
    public function add(int $id, Request $request, MeubleRepository $meubleRepo): Response
    {
        $meuble = $meubleRepo->find($id);
        if (!$meuble) {
            throw $this->createNotFoundException('Meuble introuvable.');
        }

        $panier = $request->getSession()->get('panier', []);
        $panier[$id] = ($panier[$id] ?? 0) + 1;
        $request->getSession()->set('panier', $panier);

        $this->addFlash('success', '"' . $meuble->getNom() . '" ajouté au panier.');
        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/update/{id}', name: 'app_panier_update', methods: ['POST'])]
    public function update(int $id, Request $request): Response
    {
        $quantite = (int) $request->request->get('quantite', 1);
        $panier = $request->getSession()->get('panier', []);

        if ($quantite <= 0) {
            unset($panier[$id]);
        } else {
            $panier[$id] = $quantite;
        }

        $request->getSession()->set('panier', $panier);
        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/remove/{id}', name: 'app_panier_remove')]
    public function remove(int $id, Request $request): Response
    {
        $panier = $request->getSession()->get('panier', []);
        unset($panier[$id]);
        $request->getSession()->set('panier', $panier);

        $this->addFlash('success', 'Article retiré du panier.');
        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/clear', name: 'app_panier_clear')]
    public function clear(Request $request): Response
    {
        $request->getSession()->set('panier', []);
        $this->addFlash('success', 'Panier vidé.');
        return $this->redirectToRoute('app_panier');
    }
}