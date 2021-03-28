<?php

namespace App\Controller;

use App\Entity\MediaUtilisateur;
use App\Form\MediaUtilisateurType;
use App\Repository\MediaUtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/media/utilisateur')]
class MediaUtilisateurController extends AbstractController
{
    #[Route('/', name: 'media_utilisateur_index', methods: ['GET'])]
    public function index(MediaUtilisateurRepository $mediaUtilisateurRepository): Response
    {
        return $this->render('media_utilisateur/index.html.twig', [
            'media_utilisateurs' => $mediaUtilisateurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'media_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $mediaUtilisateur = new MediaUtilisateur();
        $form = $this->createForm(MediaUtilisateurType::class, $mediaUtilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mediaUtilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('media_utilisateur_index');
        }

        return $this->render('media_utilisateur/new.html.twig', [
            'media_utilisateur' => $mediaUtilisateur,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'media_utilisateur_show', methods: ['GET'])]
    public function show(MediaUtilisateur $mediaUtilisateur): Response
    {
        return $this->render('media_utilisateur/show.html.twig', [
            'media_utilisateur' => $mediaUtilisateur,
        ]);
    }

    #[Route('/{id}/edit', name: 'media_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MediaUtilisateur $mediaUtilisateur): Response
    {
        $form = $this->createForm(MediaUtilisateurType::class, $mediaUtilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('media_utilisateur_index');
        }

        return $this->render('media_utilisateur/edit.html.twig', [
            'media_utilisateur' => $mediaUtilisateur,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'media_utilisateur_delete', methods: ['DELETE'])]
    public function delete(Request $request, MediaUtilisateur $mediaUtilisateur): Response
    {
        if ($this->isCsrfTokenValid('delete' . $mediaUtilisateur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mediaUtilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('media_utilisateur_index');
    }
}