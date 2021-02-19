<?php

namespace App\Controller;

use App\Entity\EvenementRealiser;
use App\Form\EvenementRealiserType;
use App\Repository\EvenementRealiserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/evenement/realiser")
 */
class EvenementRealiserController extends AbstractController
{
    /**
     * @Route("/", name="evenement_realiser_index", methods={"GET"})
     */
    public function index(EvenementRealiserRepository $evenementRealiserRepository): Response
    {
        return $this->render('evenement_realiser/index.html.twig', [
            'evenement_realisers' => $evenementRealiserRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="evenement_realiser_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $evenementRealiser = new EvenementRealiser();
        $form = $this->createForm(EvenementRealiserType::class, $evenementRealiser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($evenementRealiser);
            $entityManager->flush();

            return $this->redirectToRoute('evenement_realiser_index');
        }

        return $this->render('evenement_realiser/new.html.twig', [
            'evenement_realiser' => $evenementRealiser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="evenement_realiser_show", methods={"GET"})
     */
    public function show(EvenementRealiser $evenementRealiser): Response
    {
        return $this->render('evenement_realiser/show.html.twig', [
            'evenement_realiser' => $evenementRealiser,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="evenement_realiser_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EvenementRealiser $evenementRealiser): Response
    {
        $form = $this->createForm(EvenementRealiserType::class, $evenementRealiser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('evenement_realiser_index');
        }

        return $this->render('evenement_realiser/edit.html.twig', [
            'evenement_realiser' => $evenementRealiser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="evenement_realiser_delete", methods={"DELETE"})
     */
    public function delete(Request $request, EvenementRealiser $evenementRealiser): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenementRealiser->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($evenementRealiser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('evenement_realiser_index');
    }
}
