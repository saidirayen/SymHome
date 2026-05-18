<?php

namespace App\Controller;

use App\Entity\Meuble;
use App\Form\Admin\AdminMeubleType;
use App\Repository\MeubleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/meubles')]
class AdminMeubleController extends AbstractController
{
    #[Route('', name: 'admin_meuble_index', methods: ['GET'])]
    public function index(MeubleRepository $meubleRepository): Response
    {
        return $this->render('admin_meuble/index.html.twig', [
            'meubles' => $meubleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_meuble_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $meuble = new Meuble();
        $form = $this->createForm(AdminMeubleType::class, $meuble);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($meuble);
            $em->flush();
            $this->addFlash('success', 'Meuble ajouté avec succès.');
            return $this->redirectToRoute('admin_meuble_index');
        }

        return $this->render('admin_meuble/new.html.twig', [
            'meuble' => $meuble,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_meuble_show', methods: ['GET'])]
    public function show(Meuble $meuble): Response
    {
        return $this->render('admin_meuble/show.html.twig', [
            'meuble' => $meuble,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_meuble_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Meuble $meuble, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AdminMeubleType::class, $meuble);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Meuble modifié avec succès.');
            return $this->redirectToRoute('admin_meuble_index');
        }

        return $this->render('admin_meuble/edit.html.twig', [
            'meuble' => $meuble,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_meuble_delete', methods: ['POST'])]
    public function delete(Request $request, Meuble $meuble, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$meuble->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($meuble);
            $em->flush();
            $this->addFlash('success', 'Meuble supprimé.');
        }

        return $this->redirectToRoute('admin_meuble_index');
    }
}