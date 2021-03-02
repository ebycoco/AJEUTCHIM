<?php

namespace App\Controller;

use App\Entity\Bilan;
use App\Entity\Depense;
use App\Form\DepenseType;
use App\Repository\BilanRepository;
use App\Repository\DecaisementRepository;
use App\Repository\DepenseRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/depense")
 */
class DepenseController extends AbstractController
{
    /**
     * @Route("/", name="depense_index", methods={"GET"})
     */
    public function index(DepenseRepository $depenseRepository, DecaisementRepository $decaisementRepository): Response
    {
        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));
        $depenses = $depenseRepository->findByAnne($annee, 1);
        
        return $this->render('depense/index.html.twig', [
            'depenses' => $depenses,
        ]);
    }

    /**
     * @Route("/presi/confirme", name="confirme_presi", methods={"GET"})
     */
    public function confirme_presi(DepenseRepository $depenseRepository): Response
    {
        return $this->render('depense/confirme_presi.html.twig', [
            'depenses' => $depenseRepository->findByEncour(),
        ]);
    }

    /**
     * @Route("/new", name="depense_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $depense = new Depense();
       
        $form = $this->createForm(DepenseType::class, $depense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jouj = new DateTime('now');
            $annee = $jouj->format(date('Y')); 
            $entityManager = $this->getDoctrine()->getManager();
            $depense->setConfirme(false);
            $depense->setAnnee($annee);
            $depense->setEtat(0);
            $depense->setVisible(false);
            $depense->setUser($this->getUser());
            $entityManager->persist($depense); 
            $entityManager->flush();

            return $this->redirectToRoute('depense_index');
        }

        return $this->render('depense/new.html.twig', [
            'depense' => $depense,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="depense_show", methods={"GET"})
     */
    public function show(Depense $depense): Response
    {
        return $this->render('depense/show.html.twig', [
            'depense' => $depense,
        ]);
    }

    /**
     * @Route("/accepter/{id}", name="depense_accepter", methods={"GET"})
     */
    public function confirme(Depense $depense): Response
    {

        if ($depense->getEtat() == 0) {
            $depense->setConfirme(($depense->getConfirme()) ? false : true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($depense);
            $entityManager->flush();
            return $this->redirectToRoute('depense_index');
        } else {
            $this->addFlash('info','Vous ne pouvez pas car le project vient d\'être accepter par le president !');
            return $this->redirectToRoute('depense_index');
        }
    }

      /**
     * @Route("/renvoyer/{id}", name="depense_renvoyer", methods={"GET"})
     */
    public function renvoyer(Depense $depense): Response
    {

        if ($depense->getEtat() == 0) { 
            $entityManager = $this->getDoctrine()->getManager();
            $depense->setConfirme(true);
            $depense->setRejeter(false);
            $entityManager->persist($depense);
            $entityManager->flush();
            return $this->redirectToRoute('depense_index');
        } else {
            $this->addFlash(
                'info',
                'Vous ne pouvez pas car le project vient d\'être accepter par le president !'
            );
            return $this->redirectToRoute('depense_index');
        }
    }

      /**
     * @Route("/lever/{id}", name="depense_lever", methods={"GET"})
     */
    public function lever(Depense $depense): Response
    {

        if ($depense->getEtat() == 0) { 
            $entityManager = $this->getDoctrine()->getManager();
            $depense->setVisible(true); 
            $entityManager->persist($depense);
            $entityManager->flush();
            return $this->redirectToRoute('depense_index');
        } else {
            $this->addFlash(
                'info',
                'Vous ne pouvez pas car le project vient d\'être accepter par le president !'
            );
            return $this->redirectToRoute('depense_index');
        }
    }

    /**
     * @Route("/confirme/presi/{id}", name="depense_confirme", methods={"GET"})
     */
    public function confirmePresi(Depense $depense): Response
    {
        $depense->setEtat(($depense->getEtat() == 0) ? true : false);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($depense);
        $entityManager->flush();
        // return new Response("true");
        return $this->redirectToRoute('confirme_presi');
    }

    /**
     * @Route("/{id}/edit", name="depense_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Depense $depense): Response
    {
        $form = $this->createForm(DepenseType::class, $depense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $depense->setConfirme(true);
            $entityManager->persist($depense);
            $entityManager->flush();

            return $this->redirectToRoute('depense_index');
        }

        return $this->render('depense/edit.html.twig', [
            'depense' => $depense,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="depense_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Depense $depense): Response
    {
        if ($this->isCsrfTokenValid('delete' . $depense->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($depense);
            $entityManager->flush();
        }

        return $this->redirectToRoute('depense_index');
    }
}