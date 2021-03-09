<?php

namespace App\Controller\Admin;

use App\Entity\MontantAnnuelle;
use App\Form\MontantAnnuelleType;
use App\Repository\MontantAnnuelleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/montant/annuelle")
 */
class MontantAnnuelleController extends AbstractController
{
    /**
     * @Route("/", name="montant_annuelle_index", methods={"GET"})
     */
    public function index(MontantAnnuelleRepository $montantAnnuelleRepository): Response
    {
        return $this->render('admin/montant_annuelle/index.html.twig', [
            'montant_annuelles' => $montantAnnuelleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="montant_annuelle_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $montantAnnuelle = new MontantAnnuelle();
        $form = $this->createForm(MontantAnnuelleType::class, $montantAnnuelle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $montantAnnuelle->setUser($this->getUser());
            $entityManager->persist($montantAnnuelle);
            $entityManager->flush();

            return $this->redirectToRoute('montant_annuelle_index');
        }

        return $this->render('admin/montant_annuelle/new.html.twig', [
            'montant_annuelle' => $montantAnnuelle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="montant_annuelle_show", methods={"GET"})
     */
    public function show(MontantAnnuelle $montantAnnuelle): Response
    {
        return $this->render('admin/montant_annuelle/show.html.twig', [
            'montant_annuelle' => $montantAnnuelle,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="montant_annuelle_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MontantAnnuelle $montantAnnuelle): Response
    {
        $form = $this->createForm(MontantAnnuelleType::class, $montantAnnuelle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('montant_annuelle_index');
        }

        return $this->render('admin/montant_annuelle/edit.html.twig', [
            'montant_annuelle' => $montantAnnuelle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="montant_annuelle_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MontantAnnuelle $montantAnnuelle): Response
    {
        if ($this->isCsrfTokenValid('delete' . $montantAnnuelle->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($montantAnnuelle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('montant_annuelle_index');
    }
}