<?php

namespace App\Controller;

use App\Entity\Mandat;
use App\Entity\President;
use App\Form\PresidentFinMandatType;
use App\Form\PresidentType;
use App\Repository\PresidentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/president")
 */
class PresidentController extends AbstractController
{
    /**
     * @Route("/", name="president_index", methods={"GET"})
     */
    public function index(PresidentRepository $presidentRepository): Response
    {
        return $this->render('president/index.html.twig', [
            'presidents' => $presidentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="president_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $president = new President();
        $form = $this->createForm(PresidentType::class, $president);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $president->setEtat(0);
            $president->setUser($this->getUser());
            $entityManager->persist($president);
            $entityManager->flush();

            return $this->redirectToRoute('president_index');
        }

        return $this->render('president/new.html.twig', [
            'president' => $president,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="president_show", methods={"GET"})
     */
    public function show(President $president): Response
    {
        return $this->render('president/show.html.twig', [
            'president' => $president,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="president_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, President $president): Response
    {
        $form = $this->createForm(PresidentFinMandatType::class, $president);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (($president->getFinedAt() == null)) {
                return $this->redirectToRoute('president_index');
            }
            $entityManager = $this->getDoctrine()->getManager();
            $president->setEtat(1);
            $entityManager->flush();

            return $this->redirectToRoute('president_index');
        }

        return $this->render('president/edit.html.twig', [
            'president' => $president,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}/active", name="president_active", methods={"GET","POST"})
     */
    public function active(Request $request, President $president, Mandat $mandat): Response
    {
        $president = new President();
        $form = $this->createForm(PresidentType::class, $president);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        $president->setDebutedAt($mandat->getMembre()->getcreatedAt());
        $president->setcreatedAt($mandat->getMembre()->getcreatedAt());
        $president->setUpdatedAt($mandat->getMembre()->getcreatedAt());
        $president->setUser($this->getUser());
        $mandat->setActive(true);
        $entityManager->persist($president);
        $entityManager->flush();

        return $this->redirectToRoute('president_index');
    }

    /**
     * @Route("/{id}", name="president_delete", methods={"DELETE"})
     */
    public function delete(Request $request, President $president): Response
    {
        if ($this->isCsrfTokenValid('delete' . $president->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($president);
            $entityManager->flush();
        }

        return $this->redirectToRoute('president_index');
    }
}