<?php

namespace App\Controller;

use App\Entity\Depense;
use App\Form\DepenseType;
use App\Repository\DecaisementRepository;
use App\Repository\DepenseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/depense")
 */
class DepenseController extends AbstractController
{
    /**
     * @Route("/", name="depense_index", methods={"GET"})
     */
    public function index(DepenseRepository $depenseRepository, DecaisementRepository $decaisementRepository): Response
    {
        return $this->render('depense/index.html.twig', [
            'depenses' => $depenseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/presi/confirme", name="confirme_presi", methods={"GET"})
     */
    public function confirme_presi(DepenseRepository $depenseRepository): Response
    {
        return $this->render('depense/confirme_presi.html.twig', [
            'depenses' => $depenseRepository->findByEncour(),
        ]);
    }

    /**
     * @Route("/new", name="depense_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $depense = new Depense();
        $form = $this->createForm(DepenseType::class, $depense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $depense->setConfirme(false);
            $depense->setEtat(0);
            $depense->setUser($this->getUser());
            $entityManager->persist($depense);
            $entityManager->flush();

            return $this->redirectToRoute('depense_index');
        }

        return $this->render('depense/new.html.twig', [
            'depense' => $depense,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="depense_show", methods={"GET"})
     */
    public function show(Depense $depense): Response
    {
        return $this->render('depense/show.html.twig', [
            'depense' => $depense,
        ]);
    }

    /**
     * @Route("/accepter/{id}", name="depense_accepter", methods={"GET"})
     */
    public function confirme(Depense $depense): Response
    {
        $depense->setConfirme(($depense->getConfirme()) ? false : true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($depense);
        $entityManager->flush();
        return new Response("true");
    }

    /**
     * @Route("/confirme/presi/{id}", name="depense_confirme", methods={"GET"})
     */
    public function confirmePresi(Depense $depense): Response
    {
        $depense->setEtat(($depense->getEtat() == 0) ? true : false);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($depense);
        $entityManager->flush();
        return new Response("true");
    }

    /**
     * @Route("/{id}/edit", name="depense_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Depense $depense): Response
    {
        $form = $this->createForm(DepenseType::class, $depense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $depense->setConfirme(true);
            $entityManager->persist($depense);
            $entityManager->flush();

            return $this->redirectToRoute('depense_index');
        }

        return $this->render('depense/edit.html.twig', [
            'depense' => $depense,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="depense_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Depense $depense): Response
    {
        if ($this->isCsrfTokenValid('delete' . $depense->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($depense);
            $entityManager->flush();
        }

        return $this->redirectToRoute('depense_index');
    }
}