<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use App\Repository\LigneCommandeRepository;
use App\Repository\MeubleRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin')]
class AdminDashboardController extends AbstractController
{
    #[Route('', name: 'admin_dashboard')]
    public function index(
        CommandeRepository $commandeRepo,
        UserRepository $userRepo,
        MeubleRepository $meubleRepo,
        LigneCommandeRepository $ligneRepo
    ): Response {
        $commandes = $commandeRepo->findAll();

        // Chiffre d'affaires total
        $chiffreAffaires = array_sum(array_map(fn($c) => $c->getTotal(), $commandes));

        // Clients seulement
        $allUsers = $userRepo->findAll();
        $nbClients = count(array_filter($allUsers, fn($u) => !in_array('ROLE_ADMIN', $u->getRoles())));

        // Répartition par statut
        $statutStats = ['en_attente' => 0, 'en_cours' => 0, 'completee' => 0, 'annulee' => 0];
        foreach ($commandes as $c) {
            $s = $c->getStatut();
            if (isset($statutStats[$s])) {
                $statutStats[$s]++;
            }
        }

        // Chiffre d'affaires par mois
        $caParMoisRaw = [];
        foreach ($commandes as $c) {
            $mois = $c->getDateCreation()->format('Y-m');
            $caParMoisRaw[$mois] = ($caParMoisRaw[$mois] ?? 0) + $c->getTotal();
        }
        ksort($caParMoisRaw);
        $caParMois = array_map(
            fn($mois, $total) => ['mois' => $mois, 'total' => $total],
            array_keys($caParMoisRaw),
            array_values($caParMoisRaw)
        );

        // Top 5 meubles vendus
        $topMeubles = $ligneRepo->findTopMeubles(5);

        return $this->render('dashboard/index.html.twig', [
            'chiffreAffaires' => array_sum($caParMoisRaw),
            'nbClients' => $nbClients,
            'nbCommandes' => count($commandes),
            'nbMeubles' => count($meubleRepo->findAll()),
            'statutStats' => $statutStats,
            'topMeubles' => $topMeubles,
            'caParMois' => $caParMois,
        ]);
    }
}