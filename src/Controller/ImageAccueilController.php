<?php

namespace App\Controller;

use App\Entity\ImageAccueil;
use App\Form\ImageAccueilType;
use App\Repository\ImageAccueilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/image/accueil")
 */
class ImageAccueilController extends AbstractController
{
    /**
     * @Route("/", name="image_accueil_index", methods={"GET"})
     */
    public function index(ImageAccueilRepository $imageAccueilRepository): Response
    {
        return $this->render('image_accueil/index.html.twig', [
            'image_accueils' => $imageAccueilRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="image_accueil_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $imageAccueil = new ImageAccueil();
        $form = $this->createForm(ImageAccueilType::class, $imageAccueil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($imageAccueil);
            $entityManager->flush();

            return $this->redirectToRoute('image_accueil_index');
        }

        return $this->render('image_accueil/new.html.twig', [
            'image_accueil' => $imageAccueil,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="image_accueil_show", methods={"GET"})
     */
    public function show(ImageAccueil $imageAccueil): Response
    {
        return $this->render('image_accueil/show.html.twig', [
            'image_accueil' => $imageAccueil,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="image_accueil_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ImageAccueil $imageAccueil): Response
    {
        $form = $this->createForm(ImageAccueilType::class, $imageAccueil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('image_accueil_index');
        }

        return $this->render('image_accueil/edit.html.twig', [
            'image_accueil' => $imageAccueil,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="image_accueil_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ImageAccueil $imageAccueil): Response
    {
        if ($this->isCsrfTokenValid('delete'.$imageAccueil->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($imageAccueil);
            $entityManager->flush();
        }

        return $this->redirectToRoute('image_accueil_index');
    }
}
