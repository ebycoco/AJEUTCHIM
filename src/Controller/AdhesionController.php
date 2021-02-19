<?php

namespace App\Controller;

use App\Entity\Adhesion;
use App\Form\AdhesionType;
use App\Repository\AdhesionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/adhesion")
 */
class AdhesionController extends AbstractController
{
    /**
     * @Route("/", name="adhesion_index", methods={"GET"})
     */
    public function index(AdhesionRepository $adhesionRepository): Response
    {
        return $this->render('adhesion/index.html.twig', [
            'adhesions' => $adhesionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="adhesion_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $adhesion = new Adhesion();
        $form = $this->createForm(AdhesionType::class, $adhesion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $adhesion->setUser($this->getUser());
            $entityManager->persist($adhesion);
            $entityManager->flush();

            return $this->redirectToRoute('adhesion_index');
        }

        return $this->render('adhesion/new.html.twig', [
            'adhesion' => $adhesion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="adhesion_show", methods={"GET"})
     */
    public function show(Adhesion $adhesion): Response
    {
        return $this->render('adhesion/show.html.twig', [
            'adhesion' => $adhesion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="adhesion_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Adhesion $adhesion): Response
    {
        $form = $this->createForm(AdhesionType::class, $adhesion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('adhesion_index');
        }

        return $this->render('adhesion/edit.html.twig', [
            'adhesion' => $adhesion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="adhesion_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Adhesion $adhesion): Response
    {
        if ($this->isCsrfTokenValid('delete' . $adhesion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($adhesion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('adhesion_index');
    }
}