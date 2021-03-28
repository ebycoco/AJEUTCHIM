<?php

namespace App\Controller\Admin;

use App\Entity\Desactive;
use App\Form\DesactiveType;
use App\Repository\CandidatRepository;
use App\Repository\DesactiveRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/desactiver')]
class DesactiveController extends AbstractController
{
    #[Route('/', name: 'desactive_index', methods: ['GET'])]
    public function index(DesactiveRepository $desactiveRepository, CandidatRepository $candidatRepository): Response
    {

        $desactives = $desactiveRepository->findAll();
        $candidats = $candidatRepository->findAll();
        $mainte = new DateTime("now");
        if (!empty($desactive)) {
            $debut = $desactive[count($desactive) - 1]->getDebut();
            $fin = $desactive[count($desactive) - 1]->getFin();
            $interval = $debut->diff($fin);
            $reste = $interval->format('%D');
            $entityManager = $this->getDoctrine()->getManager();
            if ($fin > $mainte) {
                $activee = 1;
                //$desactive[count($desactive) - 1]->setEtat(true);
            } else {
                $activee = 0;
                //$desactive[count($desactive) - 1]->setEtat(false);
            }
            $entityManager->flush();
        } else {
            $reste = 0;
            $activee = 0;
        }

        return $this->render('admin/desactive/index.html.twig', [
            'desactives' => $desactives,
            'candidats' => $candidats,
            'activee' => $activee,
            'reste' => $reste,
        ]);
    }

    #[Route('/lien', name: 'desactive_lien', methods: ['GET', 'POST'])]
    public function lien(Request $request): Response
    {
        $mainte = new DateTime("now");
        $desactive = new Desactive();
        $form = $this->createForm(DesactiveType::class, $desactive);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $debut = $desactive->getDebut();
            $fin = $desactive->getFin();
            if ($debut > $fin) {
                $this->addFlash('warning', 'La date du debut est trop grande a celle de la fin !');
                return $this->redirectToRoute('desactive_index');
            }
            $entityManager = $this->getDoctrine()->getManager();
            $desactive->setNom('Lien');
            $desactive->setEtat(false);
            $entityManager->persist($desactive);
            $entityManager->flush();

            return $this->redirectToRoute('desactive_index');
        }

        return $this->render('admin/desactive/lien.html.twig', [
            'desactive' => $desactive,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/Ouverture/Candidature', name: 'desactive_ouvertureCandidature', methods: ['GET', 'POST'])]
    public function ouvertureCandidature(Request $request): Response
    {
        $mainte = new DateTime("now");
        $desactive = new Desactive();
        $form = $this->createForm(DesactiveType::class, $desactive);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $debut = $desactive->getDebut();
            $fin = $desactive->getFin();
            if ($debut > $fin) {
                $this->addFlash('warning', 'La date du debut est trop grande a celle de la fin !');
                return $this->redirectToRoute('desactive_index');
            }
            $entityManager = $this->getDoctrine()->getManager();
            $desactive->setNom('Candidature');
            $desactive->setEtat(false);
            $entityManager->persist($desactive);
            $entityManager->flush();

            return $this->redirectToRoute('desactive_index');
        }

        return $this->render('admin/desactive/candidature.html.twig', [
            'desactive' => $desactive,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/Ouverture/Vote', name: 'desactive_ouvertureVote', methods: ['GET', 'POST'])]
    public function ouvertureVote(Request $request): Response
    {
        $mainte = new DateTime("now");
        $desactive = new Desactive();
        $form = $this->createForm(DesactiveType::class, $desactive);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $debut = $desactive->getDebut();
            $fin = $desactive->getFin();
            if ($debut > $fin) {
                $this->addFlash('warning', 'La date du debut est trop grande a celle de la fin !');
                return $this->redirectToRoute('desactive_index');
            }
            $entityManager = $this->getDoctrine()->getManager();
            $desactive->setNom('Vote');
            $desactive->setEtat(false);
            $entityManager->persist($desactive);
            $entityManager->flush();

            return $this->redirectToRoute('desactive_index');
        }

        return $this->render('admin/desactive/candidature.html.twig', [
            'desactive' => $desactive,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/etat/{id}', name: 'desactive_etat', methods: ['GET'])]
    public function etat(Desactive $desactive, DesactiveRepository $desactiveRepository): Response
    {
        if ($desactive->getNom() == "Candidature") {
            $candidature = $desactiveRepository->findAll();
            for ($p = 0; $p < count($candidature); $p++) {
                if ($candidature[$p]->getNom() == "Vote") {
                    if ($candidature[$p]->getEtat() == true) {
                        $candidature[$p]->setEtat(false);
                    }
                }
            }
        } elseif ($desactive->getNom() == "Vote") {
            $candidature = $desactiveRepository->findAll();
            for ($p = 0; $p < count($candidature); $p++) {
                if ($candidature[$p]->getNom() == "Candidature") {
                    if ($candidature[$p]->getEtat() == true) {
                        $candidature[$p]->setEtat(false);
                    }
                }
            }
        }
        $desactive->setEtat(($desactive->getEtat() == 0) ? true : false);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('desactive_index');
    }

    #[Route('/{id}', name: 'desactive_show', methods: ['GET'])]
    public function show(Desactive $desactive): Response
    {
        return $this->render('admin/desactive/show.html.twig', [
            'desactive' => $desactive,
        ]);
    }

    #[Route('/{id}/edit', name: 'desactive_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Desactive $desactive): Response
    {
        $form = $this->createForm(DesactiveType::class, $desactive);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('desactive_index');
        }

        return $this->render('admin/desactive/edit.html.twig', [
            'desactive' => $desactive,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'desactive_delete', methods: ['DELETE'])]
    public function delete(Request $request, Desactive $desactive): Response
    {
        if ($this->isCsrfTokenValid('delete' . $desactive->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($desactive);
            $entityManager->flush();
        }

        return $this->redirectToRoute('desactive_index');
    }
}