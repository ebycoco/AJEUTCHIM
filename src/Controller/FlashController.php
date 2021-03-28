<?php

namespace App\Controller;

use App\Entity\Flash;
use App\Form\FlashType;
use App\Repository\FlashRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/flash")
 */
class FlashController extends AbstractController
{
    /**
     * @Route("/", name="flash_index", methods={"GET"})
     */
    public function index(FlashRepository $flashRepository): Response
    {
        return $this->render('flash/index.html.twig', [
            'flashes' => $flashRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="flash_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $flash = new Flash();
        $form = $this->createForm(FlashType::class, $flash);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $flash->setUser($this->getUser());
            $entityManager->persist($flash);
            $entityManager->flush();

            return $this->redirectToRoute('flash_index');
        }

        return $this->render('flash/new.html.twig', [
            'flash' => $flash,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="flash_show", methods={"GET"})
     */
    public function show(Flash $flash): Response
    {
        return $this->render('flash/show.html.twig', [
            'flash' => $flash,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="flash_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Flash $flash): Response
    {
        $form = $this->createForm(FlashType::class, $flash);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('flash_index');
        }

        return $this->render('flash/edit.html.twig', [
            'flash' => $flash,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="flash_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Flash $flash): Response
    {
        if ($this->isCsrfTokenValid('delete' . $flash->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($flash);
            $entityManager->flush();
        }

        return $this->redirectToRoute('flash_index');
    }
}