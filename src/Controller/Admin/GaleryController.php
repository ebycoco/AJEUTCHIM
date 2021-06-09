<?php

namespace App\Controller\Admin;

use App\Entity\Galery;
use App\Form\GaleryType;
use App\Repository\GaleryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/galery')]
class GaleryController extends AbstractController
{
    #[Route('/', name: 'galery_index', methods: ['GET'])]
    public function index(GaleryRepository $galeryRepository): Response
    {
        return $this->render('admin/galery/index.html.twig', [
            'galeries' => $galeryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'galery_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $galery = new Galery();
        $form = $this->createForm(GaleryType::class, $galery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $galery->setUser($this->getUser());
            $entityManager->persist($galery);
            $entityManager->flush();
            $this->addFlash('success', 'Ajouter avec success !');
            return $this->redirectToRoute('galery_index');
        }

        return $this->render('admin/galery/new.html.twig', [
            'galery' => $galery,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'galery_show', methods: ['GET'])]
    public function show(Galery $galery): Response
    {
        return $this->render('admin/galery/show.html.twig', [
            'galery' => $galery,
        ]);
    }

    #[Route('/{id}/edit', name: 'galery_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Galery $galery): Response
    {
        $form = $this->createForm(GaleryType::class, $galery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('galery_index');
        }
        $this->addFlash('success', 'Modifier avec success !');
        return $this->render('admin/galery/edit.html.twig', [
            'galery' => $galery,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'galery_delete', methods: ['DELETE'])]
    public function delete(Request $request, Galery $galery): Response
    {
        if ($this->isCsrfTokenValid('delete' . $galery->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($galery);
            $entityManager->flush();
        }

        return $this->redirectToRoute('galery_index');
    }
}