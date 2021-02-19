<?php

namespace App\Controller;

use App\Entity\Ajeutchim;
use App\Form\AjeutchimType;
use App\Repository\AjeutchimRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ajeutchim")
 */
class AjeutchimController extends AbstractController
{
    /**
     * @Route("/", name="ajeutchim_index", methods={"GET"})
     */
    public function index(AjeutchimRepository $ajeutchimRepository): Response
    {
        return $this->render('ajeutchim/index.html.twig', [
            'ajeutchims' => $ajeutchimRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ajeutchim_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $ajeutchim = new Ajeutchim();
        $form = $this->createForm(AjeutchimType::class, $ajeutchim);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ajeutchim);
            $entityManager->flush();

            return $this->redirectToRoute('ajeutchim_index');
        }

        return $this->render('ajeutchim/new.html.twig', [
            'ajeutchim' => $ajeutchim,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ajeutchim_show", methods={"GET"})
     */
    public function show(Ajeutchim $ajeutchim): Response
    {
        return $this->render('ajeutchim/show.html.twig', [
            'ajeutchim' => $ajeutchim,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ajeutchim_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Ajeutchim $ajeutchim): Response
    {
        $form = $this->createForm(AjeutchimType::class, $ajeutchim);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ajeutchim_index');
        }

        return $this->render('ajeutchim/edit.html.twig', [
            'ajeutchim' => $ajeutchim,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ajeutchim_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Ajeutchim $ajeutchim): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ajeutchim->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ajeutchim);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ajeutchim_index');
    }
}
