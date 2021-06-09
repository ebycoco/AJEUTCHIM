<?php

namespace App\Controller\Admin;

use App\Entity\Candidat;
use App\Entity\Candidature;
use App\Form\CandidatureType;
use App\Repository\CandidatureRepository;
use App\Repository\MembreRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/candidature')]
class CandidatureController extends AbstractController
{
    #[Route('/', name: 'candidature_index', methods: ['GET'])]
    public function index(CandidatureRepository $candidatureRepository): Response
    {
        return $this->render('admin/candidature/index.html.twig', [
            'candidatures' => $candidatureRepository->findByCandidature(),
        ]);
    }

    #[Route('/new', name: 'candidature_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MembreRepository $membreRepository, CandidatureRepository $candidatureRepository): Response
    {
        $listeCandidat = $candidatureRepository->findAll();
        $candidature = new Candidature();
        $form = $this->createForm(CandidatureType::class, $candidature);
        $form->handleRequest($request);
        $matriculeUser = $this->getUser()->getMatricule();
        if ($matriculeUser == null) {
            $this->addFlash('danger', 'Vous ne pouvez pas déposer de candidature car vous être administrateur!');
            return $this->redirectToRoute('app_profile');
        }
        for ($i = 0; $i < count($listeCandidat); $i++) {
            $matriculeList = $listeCandidat[$i]->getMatriculeAjeutchim();
            if ($matriculeUser == $matriculeList) {
                $this->addFlash('danger', 'Vous ne pouvez plus poser de candidature car votre candidature est en cours de traitement !');
                return $this->redirectToRoute('app_profile');
            }
        }


        if ($form->isSubmitted() && $form->isValid()) {

            $membre = $membreRepository->findAll();
            $voire = $matriculeUser;

            $peut = null;
            for ($i = 0; $i < count($membre); $i++) {
                $matricul = $membre[$i]->getReferenceAjeutchim();
                if ($voire == $matricul) {
                    $peut = $matricul;
                }
            }
            if ($peut == null) {
                $this->addFlash('warning', 'Votre matricule est invalid !');
                return $this->redirectToRoute('app_profile');
            }
            $entityManager = $this->getDoctrine()->getManager();
            $candidature->setMatriculeAjeutchim($matriculeUser);
            $candidature->setDroit(0);
            $entityManager->persist($candidature);
            $entityManager->flush();
            $this->addFlash('success', 'Votre candidature a été envoyé avec success !');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/candidature.html.twig', [
            'candidature' => $candidature,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'candidature_show', methods: ['GET'])]
    public function show(Candidature $candidature): Response
    {
        return $this->render('admin/candidature/show.html.twig', [
            'candidature' => $candidature,
        ]);
    }

    #[Route('/{id}/edit', name: 'candidature_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Candidature $candidature): Response
    {
        $form = $this->createForm(CandidatureType::class, $candidature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('candidature_index');
        }

        return $this->render('candidature/edit.html.twig', [
            'candidature' => $candidature,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/{id}/valider', name: 'candidature_valider', methods: ['GET', 'POST'])]
    public function valider(Candidature $candidature, MembreRepository $membreRepository): Response
    {
        $membre = $membreRepository->findAll();
        for ($i = 0; $i < count($membre); $i++) {
            $person = $membre[$i]->getReferenceAjeutchim();
            if ($person == $candidature->getMatriculeAjeutchim()) {
                $jouj = new DateTime('now');
                $annee = $jouj->format(date('Y'));
                $candidat = new Candidat();
                $entityManager = $this->getDoctrine()->getManager();
                $candidature->setDroit(1);
                $entityManager->persist($candidature);
                $candidat->setNom($candidature->getNom());
                $candidat->setPrenom($candidature->getPrenom());
                $candidat->setCandidature($candidature);
                $candidat->setMembre($membre[$i]);
                $candidat->setNombreVoix(0);
                $candidat->setEtat(false);
                $candidat->setAnnee($annee);
                $candidat->setTour1('1er Tour');
                $candidat->setFin(false);
                $candidat->setVuePublic(false);
                $entityManager->persist($candidat);
                $entityManager->flush();
            }
        }


        return $this->redirectToRoute('candidature_index');
    }
    #[Route('/{id}/refuser', name: 'candidature_refuser', methods: ['GET', 'POST'])]
    public function refuser(Candidature $candidature): Response
    {
        $candidature->setDroit(2);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('candidature_index');
    }

    #[Route('/{id}/enlever', name: 'candidature_enlever', methods: ['GET', 'POST'])]
    public function enlever(Candidature $candidature): Response
    {
        $candidature->setDroit(3);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('candidature_index');
    }

    #[Route('/{id}', name: 'candidature_delete', methods: ['DELETE'])]
    public function delete(Request $request, Candidature $candidature): Response
    {
        if ($this->isCsrfTokenValid('delete' . $candidature->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($candidature);
            $entityManager->flush();
        }

        return $this->redirectToRoute('candidature_index');
    }
}