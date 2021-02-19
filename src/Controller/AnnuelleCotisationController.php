<?php

namespace App\Controller;

use App\Entity\AnnuelleCotisation;
use App\Form\AnnuelleCotisationType;
use App\Repository\AnnuelleCotisationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/annuelle/cotisation")
 */
class AnnuelleCotisationController extends AbstractController
{
    /**
     * @Route("/", name="annuelle_cotisation_index", methods={"GET"})
     */
    public function index(AnnuelleCotisationRepository $annuelleCotisationRepository): Response
    {
        return $this->render('annuelle_cotisation/index.html.twig', [
            'annuelle_cotisations' => $annuelleCotisationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="annuelle_cotisation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $annuelleCotisation = new AnnuelleCotisation();
        $form = $this->createForm(AnnuelleCotisationType::class, $annuelleCotisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annuelleCotisation);
            $entityManager->flush();

            return $this->redirectToRoute('annuelle_cotisation_index');
        }

        return $this->render('annuelle_cotisation/new.html.twig', [
            'annuelle_cotisation' => $annuelleCotisation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="annuelle_cotisation_show", methods={"GET"})
     */
    public function show(AnnuelleCotisation $annuelleCotisation): Response
    {
        return $this->render('annuelle_cotisation/show.html.twig', [
            'annuelle_cotisation' => $annuelleCotisation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="annuelle_cotisation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, AnnuelleCotisation $annuelleCotisation): Response
    {
        $form = $this->createForm(AnnuelleCotisationType::class, $annuelleCotisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('annuelle_cotisation_index');
        }

        return $this->render('annuelle_cotisation/edit.html.twig', [
            'annuelle_cotisation' => $annuelleCotisation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="annuelle_cotisation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, AnnuelleCotisation $annuelleCotisation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annuelleCotisation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($annuelleCotisation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('annuelle_cotisation_index');
    }
}
