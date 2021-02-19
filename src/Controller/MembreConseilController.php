<?php

namespace App\Controller;

use App\Entity\MembreConseil;
use App\Form\MembreConseilType;
use App\Repository\MembreConseilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/membre/conseil")
 */
class MembreConseilController extends AbstractController
{
    /**
     * @Route("/", name="membre_conseil_index", methods={"GET"})
     */
    public function index(MembreConseilRepository $membreConseilRepository): Response
    {
        return $this->render('membre_conseil/index.html.twig', [
            'membre_conseils' => $membreConseilRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="membre_conseil_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $membreConseil = new MembreConseil();
        $form = $this->createForm(MembreConseilType::class, $membreConseil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($membreConseil);
            $entityManager->flush();

            return $this->redirectToRoute('membre_conseil_index');
        }

        return $this->render('membre_conseil/new.html.twig', [
            'membre_conseil' => $membreConseil,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="membre_conseil_show", methods={"GET"})
     */
    public function show(MembreConseil $membreConseil): Response
    {
        return $this->render('membre_conseil/show.html.twig', [
            'membre_conseil' => $membreConseil,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="membre_conseil_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MembreConseil $membreConseil): Response
    {
        $form = $this->createForm(MembreConseilType::class, $membreConseil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('membre_conseil_index');
        }

        return $this->render('membre_conseil/edit.html.twig', [
            'membre_conseil' => $membreConseil,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="membre_conseil_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MembreConseil $membreConseil): Response
    {
        if ($this->isCsrfTokenValid('delete'.$membreConseil->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($membreConseil);
            $entityManager->flush();
        }

        return $this->redirectToRoute('membre_conseil_index');
    }
}
