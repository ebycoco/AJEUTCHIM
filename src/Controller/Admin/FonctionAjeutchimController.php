<?php

namespace App\Controller\Admin;

use App\Entity\FonctionAjeutchim;
use App\Form\FonctionAjeutchimType;
use App\Repository\FonctionAjeutchimRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/fonction')]
class FonctionAjeutchimController extends AbstractController
{
    #[Route('/', name: 'fonction_ajeutchim_index', methods: ['GET'])]
    public function index(FonctionAjeutchimRepository $fonctionAjeutchimRepository): Response
    {
        return $this->render('admin/fonction_ajeutchim/index.html.twig', [
            'fonction_ajeutchims' => $fonctionAjeutchimRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'fonction_ajeutchim_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $fonctionAjeutchim = new FonctionAjeutchim();
        $form = $this->createForm(FonctionAjeutchimType::class, $fonctionAjeutchim);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $fonctionAjeutchim->setUser($this->getUser());
            $entityManager->persist($fonctionAjeutchim);
            $entityManager->flush();

            return $this->redirectToRoute('fonction_ajeutchim_index');
        }

        return $this->render('admin/fonction_ajeutchim/new.html.twig', [
            'fonction_ajeutchim' => $fonctionAjeutchim,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'fonction_ajeutchim_show', methods: ['GET'])]
    public function show(FonctionAjeutchim $fonctionAjeutchim): Response
    {
        return $this->render('admin/fonction_ajeutchim/show.html.twig', [
            'fonction_ajeutchim' => $fonctionAjeutchim,
        ]);
    }

    #[Route('/{id}/edit', name: 'fonction_ajeutchim_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FonctionAjeutchim $fonctionAjeutchim): Response
    {
        $form = $this->createForm(FonctionAjeutchimType::class, $fonctionAjeutchim);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('fonction_ajeutchim_index');
        }

        return $this->render('admin/fonction_ajeutchim/edit.html.twig', [
            'fonction_ajeutchim' => $fonctionAjeutchim,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'fonction_ajeutchim_delete', methods: ['DELETE'])]
    public function delete(Request $request, FonctionAjeutchim $fonctionAjeutchim): Response
    {
        if ($this->isCsrfTokenValid('delete' . $fonctionAjeutchim->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($fonctionAjeutchim);
            $entityManager->flush();
        }

        return $this->redirectToRoute('fonction_ajeutchim_index');
    }
}