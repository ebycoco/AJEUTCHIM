<?php

namespace App\Controller;

use App\Entity\DemandeAdhesion;
use App\Form\DemandeAdhesionType;
use App\Repository\DemandeAdhesionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/demande/adhesion')]
class DemandeAdhesionController extends AbstractController
{
    #[Route('/', name: 'demande_adhesion_index', methods: ['GET'])]
    public function index(DemandeAdhesionRepository $demandeAdhesionRepository): Response
    {
        return $this->render('demande_adhesion/index.html.twig', [
            'demande_adhesions' => $demandeAdhesionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'demande_adhesion_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $demandeAdhesion = new DemandeAdhesion();
        $form = $this->createForm(DemandeAdhesionType::class, $demandeAdhesion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($demandeAdhesion);
            $entityManager->flush();

            return $this->redirectToRoute('demande_adhesion_index');
        }

        return $this->render('demande_adhesion/new.html.twig', [
            'demande_adhesion' => $demandeAdhesion,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'demande_adhesion_show', methods: ['GET'])]
    public function show(DemandeAdhesion $demandeAdhesion): Response
    {
        return $this->render('demande_adhesion/show.html.twig', [
            'demande_adhesion' => $demandeAdhesion,
        ]);
    }

    #[Route('/{id}/edit', name: 'demande_adhesion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DemandeAdhesion $demandeAdhesion): Response
    {
        $form = $this->createForm(DemandeAdhesionType::class, $demandeAdhesion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('demande_adhesion_index');
        }

        return $this->render('demande_adhesion/edit.html.twig', [
            'demande_adhesion' => $demandeAdhesion,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'demande_adhesion_delete', methods: ['DELETE'])]
    public function delete(Request $request, DemandeAdhesion $demandeAdhesion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demandeAdhesion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($demandeAdhesion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('demande_adhesion_index');
    }
}
