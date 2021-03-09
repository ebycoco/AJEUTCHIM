<?php

namespace App\Controller\Admin;

use App\Entity\PostAjeutchim;
use App\Form\PostAjeutchimType;
use App\Repository\PostAjeutchimRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/post/ajeutchim")
 */
class PostAjeutchimController extends AbstractController
{
    /**
     * @Route("/", name="post_ajeutchim_index", methods={"GET"})
     */
    public function index(PostAjeutchimRepository $postAjeutchimRepository): Response
    {
        return $this->render('admin/post_ajeutchim/index.html.twig', [
            'post_ajeutchims' => $postAjeutchimRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="post_ajeutchim_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $postAjeutchim = new PostAjeutchim();
        $form = $this->createForm(PostAjeutchimType::class, $postAjeutchim);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $postAjeutchim->setUser($this->getUser());
            $entityManager->persist($postAjeutchim);
            $entityManager->flush();

            return $this->redirectToRoute('post_ajeutchim_index');
        }

        return $this->render('admin/post_ajeutchim/new.html.twig', [
            'post_ajeutchim' => $postAjeutchim,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_ajeutchim_show", methods={"GET"})
     */
    public function show(PostAjeutchim $postAjeutchim): Response
    {
        return $this->render('admin/post_ajeutchim/show.html.twig', [
            'post_ajeutchim' => $postAjeutchim,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="post_ajeutchim_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PostAjeutchim $postAjeutchim): Response
    {
        $form = $this->createForm(PostAjeutchimType::class, $postAjeutchim);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_ajeutchim_index');
        }

        return $this->render('admin/post_ajeutchim/edit.html.twig', [
            'post_ajeutchim' => $postAjeutchim,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_ajeutchim_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PostAjeutchim $postAjeutchim): Response
    {
        if ($this->isCsrfTokenValid('delete'.$postAjeutchim->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($postAjeutchim);
            $entityManager->flush();
        }

        return $this->redirectToRoute('post_ajeutchim_index');
    }
}