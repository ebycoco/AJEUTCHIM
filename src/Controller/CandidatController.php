<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Membre;
use App\Entity\Votant;
use App\Form\CandidatType;
use App\Form\VotantType;
use App\Repository\CandidatRepository;
use App\Repository\MembreRepository;
use App\Repository\VotantRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/candidat')]
class CandidatController extends AbstractController
{
    #[Route('/', name: 'candidat_index', methods: ['GET'])]
    public function index(CandidatRepository $candidatRepository): Response
    {
        $sanscandidat = $candidatRepository->findCandidatAvote();
        if (empty($sanscandidat)) {
            return $this->redirectToRoute('app_home');
        }
        return $this->render('candidat/index.html.twig', [
            'candidats' => $candidatRepository->findAll(),
        ]);
    }
    #[Route('/resultat', name: 'candidat_resultat', methods: ['GET'])]
    public function resultat(CandidatRepository $candidatRepository): Response
    {
        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));
        $autrefdecission = "non";
        $secondTour = "non";
        $tour = "1er Tour";
        $candidat = $candidatRepository->findCandidatAnnee($annee);
        if (empty($candidat)) {
            return $this->redirectToRoute('candidature_index');
        }
        for ($i = 0; $i < count($candidat); $i++) {

            if ($candidat[$i]->getTour2() == "2ème Tour") {
                $tour = $candidat[$i]->getTour2();
            }
        }
        if ($tour == "2ème Tour") {
            $candidats = $candidatRepository->findCandidatAnneeTour2($tour);
        } else {
            $candidats = $candidatRepository->findCandidatAnnee($annee);
        }
        $candidatPlusPoint = $candidatRepository->findCandidatPlusPoint($annee);
        for ($i = 0; $i < count($candidatPlusPoint); $i++) {
            $vote = $candidatPlusPoint[$i]->getNombreVoix();
        }
        $vote1 = 0;
        $vote = 0;
        for ($i = 0; $i < count($candidats); $i++) {
            $vote = $candidats[$i]->getNombreVoix2();
            if ($i == 0) {
                $vote1 = $candidats[$i]->getNombreVoix2();
            } else {
                $vote = $candidats[$i]->getNombreVoix2();
            }
            if ($vote1 == $vote) {
                $etat = "reprend oui";
            } else {
                $etat = "reprend non";
            }
        }

        $nombrecandidats = $candidatRepository->findCandidatSecond($vote);
        if (count($nombrecandidats) == 2) {
            $secondTour = "oui";
            for ($i = 0; $i < count($nombrecandidats); $i++) {
                $entityManager = $this->getDoctrine()->getManager();
                $nombrecandidats[$i]->setTour2('2ème Tour');
                $entityManager->flush();
            }
        }
        if ($etat ==  "reprend non") {
            $secondTour = "non";
            $autrefdecission = "non";
        } elseif ($etat ==  "reprend oui") {
            if ($tour == "2ème Tour") {
                $autrefdecission = "oui";
            } else {
                $autrefdecission = "non";
            }
            $secondTour = "oui";
        }
        return $this->render('candidat/index.html.twig', [
            'candidats' => $candidats,
            'autrefdecission' => $autrefdecission,
            'secondTour' => $secondTour,
        ]);
    }
    #[Route('/vote', name: 'candidat_vote', methods: ['GET', 'POST'])]
    public function vote(Request $request, CandidatRepository $candidatRepository, VotantRepository $votantRepository, MembreRepository $membreRepository, SessionInterface $session): Response
    {
        $candidat = $candidatRepository->findCandidatAvote();
        if (empty($candidat)) {
            $this->addFlash('warning', 'Le vote est terminer !');
            return $this->redirectToRoute('app_home');
        }
        $votante = $votantRepository->findAll();
        $membre = $membreRepository->findAll();
        $votant = new Votant();
        $form = $this->createForm(VotantType::class, $votant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $matriculeEntrer = $form->get('matricule')->getData();
            $peut = null;
            for ($i = 0; $i < count($votante); $i++) {
                $matricul = $votante[$i]->getMatricule();
                if ($matriculeEntrer == $matricul) {
                    $peut = $matricul;
                }
            }
            $peutMembre = null;
            for ($i = 0; $i < count($membre); $i++) {
                $matricule = $membre[$i]->getReferenceAjeutchim();
                if ($matriculeEntrer == $matricule) {
                    $peutMembre = $matricule;
                }
            }
            if ($peutMembre == null) {
                $this->addFlash('warning', 'Votre matricule est introuvable !');
                return $this->redirectToRoute('candidat_vote');
            }
            if ($matriculeEntrer != $peutMembre) {
                $this->addFlash('warning', 'Votre matricule est invalid !');
                return $this->redirectToRoute('candidat_vote');
            }
            if ($peut != null) {
                $this->addFlash('warning', 'Vous avez vote !');
                return $this->redirectToRoute('candidat_vote');
            }

            $vote = $session->get('vote');
            $vote = $matriculeEntrer;
            $session->set('vote', $vote);

            return $this->render('candidat/pagedevote.html.twig', [
                'candidats' => $candidatRepository->findCandidatAvote(),
                'matriculeEntrer' => $matriculeEntrer,
            ]);
        }

        return $this->render('votant/new.html.twig', [
            'candidats' => $candidatRepository->findAll(),
            'votant' => $votant,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/choix/{id}', name: 'candidat_choix', methods: ['GET'])]
    public function choix(Candidat $candidat, VotantRepository $votantRepository, SessionInterface $session, CandidatRepository $candidatRepository,): Response
    {
        $vote = $session->get('vote');
        $votante = $votantRepository->findAll();
        $matriculeEntrer = $vote;

        if (empty($vote)) {
            $this->addFlash('info', 'Vous devrez vous entrez votre matricule avant de passer au vote !');
            return $this->redirectToRoute('candidat_vote');
        }
        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));
        $secondTour = "non";
        $candidatPlusPoint = $candidatRepository->findCandidatPlusPoint($annee);
        for ($i = 0; $i < count($candidatPlusPoint); $i++) {
            $vote = $candidatPlusPoint[$i]->getNombreVoix();
        }
        $nombrecandidats = $candidatRepository->findCandidatSecond($vote);
        if (count($nombrecandidats) == 2) {
            for ($i = 0; $i < count($nombrecandidats); $i++) {
                $nbTour = $nombrecandidats[$i]->getTour2();
            }
            if (!empty($nbTour)) {
                $secondTour = "oui";
            }
        }
        if ($secondTour == "non") {
            $voter = $votantRepository->findVotant($vote);
            if (!empty($voter)) {
                $this->addFlash('info', 'Vous venez de votez !');
                return $this->redirectToRoute('app_home');
            }
            $votant = new Votant();
            $entityManager = $this->getDoctrine()->getManager();
            $nombreVoi = $candidat->getNombreVoix();
            $candidat->setNombreVoix($nombreVoi + 1);
            $entityManager->persist($candidat);
            $votant->setMatricule($matriculeEntrer);
            $entityManager->persist($votant);
            $entityManager->flush();
            unset($vote);
            $this->addFlash('success', 'Merci d\'avoir votez candidat ' . $candidat->getPrenom());
            return $this->redirectToRoute('app_home');
        } elseif ($secondTour = "oui") {

            $fois = 0;
            for ($i = 0; $i < count($votante); $i++) {
                $matricul = $votante[$i]->getMatricule();
                if ($matriculeEntrer == $matricul) {
                    $fois = $fois + 1;
                }
            }
            if ($fois > 1) {
                $this->addFlash('warning', 'Vous avez vote !');
                return $this->redirectToRoute('app_home');
            }
            $votant = new Votant();
            $entityManager = $this->getDoctrine()->getManager();
            $nombreVoi2 = $candidat->getNombreVoix2();
            $candidat->setNombreVoix2($nombreVoi2 + 1);
            $entityManager->persist($candidat);
            $votant->setMatricule($matriculeEntrer);
            $entityManager->persist($votant);
            $entityManager->flush();
            unset($vote);
            $this->addFlash('success', 'Merci d\'avoir votez candidat ' . $candidat->getPrenom());
            return $this->redirectToRoute('app_home');
        }
    }
    #[Route('/etat/vote', name: 'candidat_etatvote', methods: ['GET'])]
    public function etatvote(CandidatRepository $candidatRepository): Response
    {
        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));
        $fin = $candidatRepository->findCandidatAnnee($annee);
        for ($i = 0; $i < count($fin); $i++) {
            $entityManager = $this->getDoctrine()->getManager();
            $fin[$i]->setEtat(($fin[$i]->getEtat() == 0) ? true : false);
            $entityManager->flush();
        }

        return $this->redirectToRoute('candidat_resultat');
    }

    #[Route('/etat/vote/second', name: 'candidat_etatvotesecond', methods: ['GET'])]
    public function etatvotesecond(CandidatRepository $candidatRepository): Response
    {
        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));
        $fin = $candidatRepository->findCandidatAnnee($annee);
        for ($i = 0; $i < count($fin); $i++) {
            $entityManager = $this->getDoctrine()->getManager();
            $fin[$i]->setEtat(($fin[$i]->getEtat() == 0) ? true : false);
            $entityManager->flush();
        }
        $candidatPlusPoint = $candidatRepository->findCandidatPlusPoint($annee);
        for ($i = 0; $i < count($candidatPlusPoint); $i++) {
            $vote = $candidatPlusPoint[$i]->getNombreVoix();
        }
        $nombrecandidats = $candidatRepository->findCandidatSecond($vote);
        if (count($nombrecandidats) == 2) {
            for ($i = 0; $i < count($nombrecandidats); $i++) {
                $entityManager = $this->getDoctrine()->getManager();
                $nombrecandidats[$i]->setEtat(false);
                $fin[$i]->setTour2("2ème Tour");
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('candidat_resultat');
    }
    #[Route('/etat/fin', name: 'candidat_etatfin', methods: ['GET'])]
    public function etatfin(CandidatRepository $candidatRepository): Response
    {
        $fin = $candidatRepository->findAll();
        for ($i = 0; $i < count($fin); $i++) {
            $entityManager = $this->getDoctrine()->getManager();
            $fin[$i]->setFin(($fin[$i]->getFin() == 0) ? true : false);
            $fin[$i]->setEtat(true);
            $entityManager->flush();
        }

        return $this->redirectToRoute('candidat_resultat');
    }
    #[Route('/etat/public', name: 'candidat_etatpublic', methods: ['GET'])]
    public function etatpublic(CandidatRepository $candidatRepository): Response
    {
        $fin = $candidatRepository->findAll();
        for ($i = 0; $i < count($fin); $i++) {
            $entityManager = $this->getDoctrine()->getManager();
            $fin[$i]->setVuePublic(true);
            $entityManager->flush();
        }

        return $this->redirectToRoute('candidat_resultat');
    }
    #[Route('/etat/public', name: 'candidat_public', methods: ['GET'])]
    public function public(CandidatRepository $candidatRepository): Response
    {
        $fin = $candidatRepository->findAll();
        for ($i = 0; $i < count($fin); $i++) {
            $entityManager = $this->getDoctrine()->getManager();
            $fin[$i]->setVuePublic(($fin[$i]->getVuePublic() == 0) ? true : false);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_index');
    }

    #[Route('/new', name: 'candidat_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $candidat = new Candidat();
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($candidat);
            $entityManager->flush();

            return $this->redirectToRoute('candidat_index');
        }

        return $this->render('candidat/new.html.twig', [
            'candidat' => $candidat,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'candidat_show', methods: ['GET'])]
    public function show(Candidat $candidat): Response
    {
        return $this->render('candidat/show.html.twig', [
            'candidat' => $candidat,
        ]);
    }

    #[Route('/{id}/edit', name: 'candidat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Candidat $candidat): Response
    {
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('candidat_index');
        }

        return $this->render('candidat/edit.html.twig', [
            'candidat' => $candidat,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'candidat_delete', methods: ['DELETE'])]
    public function delete(Request $request, Candidat $candidat): Response
    {
        if ($this->isCsrfTokenValid('delete' . $candidat->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($candidat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('candidat_index');
    }
}