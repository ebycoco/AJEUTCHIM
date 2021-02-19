<?php

namespace App\Controller;

use App\Entity\Cotisation;
use App\Form\CotisationType;
use App\Repository\CotisationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cotisation")
 */
class CotisationController extends AbstractController
{
    /**
     * @Route("/", name="cotisation_index", methods={"GET"})
     */
    public function index(CotisationRepository $cotisationRepository): Response
    {
        return $this->render('cotisation/index.html.twig', [
            'cotisations' => $cotisationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="cotisation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cotisation = new Cotisation();
        $form = $this->createForm(CotisationType::class, $cotisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cotisation);
            $entityManager->flush();

            return $this->redirectToRoute('cotisation_index');
        }

        return $this->render('cotisation/new.html.twig', [
            'cotisation' => $cotisation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cotisation_show", methods={"GET"})
     */
    public function show(Cotisation $cotisation): Response
    {
        return $this->render('cotisation/show.html.twig', [
            'cotisation' => $cotisation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cotisation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cotisation $cotisation): Response
    {
        $form = $this->createForm(CotisationType::class, $cotisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cotisation_index');
        }

        return $this->render('cotisation/edit.html.twig', [
            'cotisation' => $cotisation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cotisation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Cotisation $cotisation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cotisation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cotisation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cotisation_index');
    }
}
