<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\LigneCommande;
use App\Form\PaiementType;
use App\Repository\MeubleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommandeController extends AbstractController
{
    #[Route('/paiement', name: 'app_paiement', methods: ['GET', 'POST'])]
    public function paiement(
        Request $request,
        MeubleRepository $meubleRepo,
        EntityManagerInterface $em
    ): Response {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $panier = $request->getSession()->get('panier', []);

        if (empty($panier)) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('app_panier');
        }

        // Build cart summary for display
        $items = [];
        $total = 0;
        foreach ($panier as $id => $quantite) {
            $meuble = $meubleRepo->find($id);
            if (!$meuble) continue;
            $sousTotal = $meuble->getPrix() * $quantite;
            $total    += $sousTotal;
            $items[]   = ['meuble' => $meuble, 'quantite' => $quantite, 'sousTotal' => $sousTotal];
        }

        $form = $this->createForm(PaiementType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commande = new Commande();
            $commande->setUser($this->getUser());
            $commande->setReference('CMD-' . strtoupper(uniqid()));
            $commande->setStatut('en_attente');
            $commande->setDateCreation(new \DateTime());

            $orderTotal = 0;
            foreach ($panier as $id => $quantite) {
                $meuble = $meubleRepo->find($id);
                if (!$meuble) continue;

                $ligne = new LigneCommande();
                $ligne->setMeuble($meuble);
                $ligne->setQuantite($quantite);
                $ligne->setPrixUnitaire($meuble->getPrix());
                $ligne->setCommande($commande);
                $orderTotal += $meuble->getPrix() * $quantite;
                $meuble->setStock($meuble->getStock() - $quantite);
                $em->persist($ligne);
            }

            $commande->setTotal($orderTotal);
            $em->persist($commande);
            $em->flush();

            $request->getSession()->set('panier', []);

            $this->addFlash('success', 'Commande ' . $commande->getReference() . ' passée avec succès !');
            return $this->redirectToRoute('app_commande_historique');
        }

        return $this->render('paiement/index.html.twig', [
            'form'  => $form->createView(),
            'items' => $items,
            'total' => $total,
        ]);
    }

    #[Route('/commande/historique', name: 'app_commande_historique')]
    public function historique(EntityManagerInterface $em): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $commandes = $em->getRepository(Commande::class)->findBy(
            ['user' => $this->getUser()],
            ['date_creation' => 'DESC']
        );

        return $this->render('commande/historique.html.twig', ['commandes' => $commandes]);
    }

    #[Route('/commande/{id}', name: 'app_commande_show')]
    public function show(int $id, EntityManagerInterface $em): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $commande = $em->getRepository(Commande::class)->find($id);

        if (!$commande || $commande->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException('Commande introuvable.');
        }

        return $this->render('commande/show.html.twig', ['commande' => $commande]);
    }
}