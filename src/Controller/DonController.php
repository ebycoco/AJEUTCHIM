<?php

namespace App\Controller;

use App\Entity\Don;
use App\Form\DonType;
use App\Repository\DonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/don")
 */
class DonController extends AbstractController
{
    /**
     * @Route("/", name="don_index", methods={"GET"})
     */
    public function index(DonRepository $donRepository): Response
    {
        return $this->render('don/index.html.twig', [
            'dons' => $donRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="don_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $don = new Don();
        $form = $this->createForm(DonType::class, $don);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($don);
            $entityManager->flush();

            return $this->redirectToRoute('don_index');
        }

        return $this->render('don/new.html.twig', [
            'don' => $don,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="don_show", methods={"GET"})
     */
    public function show(Don $don): Response
    {
        return $this->render('don/show.html.twig', [
            'don' => $don,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="don_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Don $don): Response
    {
        $form = $this->createForm(DonType::class, $don);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('don_index');
        }

        return $this->render('don/edit.html.twig', [
            'don' => $don,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="don_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Don $don): Response
    {
        if ($this->isCsrfTokenValid('delete'.$don->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($don);
            $entityManager->flush();
        }

        return $this->redirectToRoute('don_index');
    }
}
