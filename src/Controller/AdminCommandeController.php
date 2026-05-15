<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/commandes')]
class AdminCommandeController extends AbstractController
{
    #[Route('', name: 'admin_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('admin_commande/index.html.twig', [
            'commandes' => $commandeRepository->findBy([], ['date_creation' => 'DESC']),
        ]);
    }

    #[Route('/{id}', name: 'admin_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('admin_commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/statut', name: 'admin_commande_statut', methods: ['POST'])]
    public function changerStatut(Request $request, Commande $commande, EntityManagerInterface $em): Response
    {
        $statut = $request->request->get('statut');
        $valides = ['en_attente', 'en_cours', 'completee', 'annulee'];

        if (in_array($statut, $valides)) {
            $commande->setStatut($statut);
            $em->flush();
            $this->addFlash('success', 'Statut mis à jour.');
        } else {
            $this->addFlash('error', 'Statut invalide.');
        }

        return $this->redirectToRoute('admin_commande_show', ['id' => $commande->getId()]);
    }

    #[Route('/{id}/delete', name: 'admin_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($commande);
            $em->flush();
            $this->addFlash('success', 'Commande supprimée.');
        }

        return $this->redirectToRoute('admin_commande_index');
    }
}