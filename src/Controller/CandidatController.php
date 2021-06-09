<?php

namespace App\Controller;

use App\Entity\Bureau;
use App\Entity\Candidat;
use App\Entity\Desactive;
use App\Entity\Membre;
use App\Entity\President;
use App\Entity\Votant;
use App\Form\CandidatType;
use App\Form\VotantType;
use App\Repository\CandidatRepository;
use App\Repository\DesactiveRepository;
use App\Repository\MembreRepository;
use App\Repository\PostAjeutchimRepository;
use App\Repository\PresidentRepository;
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
        $public = null;
        $candidat = $candidatRepository->findCandidatAnnee($annee);
        if (empty($candidat)) {
            return $this->redirectToRoute('candidature_index');
        }
        for ($i = 0; $i < count($candidat); $i++) {

            if ($candidat[$i]->getTour2() == "2eme Tour") {
                $tour = $candidat[$i]->getTour2();
            }
        }
        if ($tour == "2eme Tour") {
            $candidats = $candidatRepository->findCandidatAnneeTour2($tour);
        } else {
            $candidats = $candidatRepository->findCandidatAnnee($annee);
        }
        $candidatPlusPoint = $candidatRepository->findCandidatPlusPoint($annee);
        $candidatPlusPointsecond = $candidatRepository->findCandidatPlusPointsecond("2eme Tour");

        for ($i = 0; $i < count($candidatPlusPoint); $i++) {
            $vote = $candidatPlusPoint[$i]->getNombreVoix();
        }
        if ($tour == "1er Tour") {
            $etat = "reprend non";
        } elseif ($tour == "2eme Tour") {
            $etat = "reprend oui";
        }

        $nombrecandidats = $candidatRepository->findCandidatSecond($vote);

        if (count($nombrecandidats) == 2) {
            $secondTour = "oui";
            for ($i = 0; $i < count($nombrecandidats); $i++) {
                $entityManager = $this->getDoctrine()->getManager();
                $nombrecandidats[$i]->setTour2('2eme Tour');
                $entityManager->flush();
            }
            if ($tour == "2eme Tour") {
                $autrefdecission = "oui";
            } else {
                $autrefdecission = "non";
            }
            $secondTour = "oui";
            $voix = 0;
            for ($i = 0; $i < count($candidatPlusPointsecond); $i++) {
                if ($voix == 0) {
                    $voix = $candidatPlusPointsecond[$i]->getNombreVoix2();
                } else {
                    $voix1 = $candidatPlusPointsecond[$i]->getNombreVoix2();
                }
            }
            if ($voix == $voix1) {
                $deci = 2;
            } else {
                $deci = 1;
            }
            for ($i = 0; $i < count($candidatPlusPointsecond); $i++) {
                $public = $candidatPlusPointsecond[$i]->getVuePublic();
            }
        } else {
            $secondTour = "non";
            $autrefdecission = "non";
            $deci = null;
            for ($i = 0; $i < count($candidatPlusPointsecond); $i++) {
                $public = $candidatPlusPointsecond[$i]->getVuePublic();
            }
        }

        return $this->render('candidat/index.html.twig', [
            'candidats' => $candidats,
            'autrefdecission' => $autrefdecission,
            'secondTour' => $secondTour,
            'deci' => $deci,
            'public' => $public,
        ]);
    }
    #[Route('/vote', name: 'candidat_vote', methods: ['GET', 'POST'])]
    public function vote(Request $request, CandidatRepository $candidatRepository, VotantRepository $votantRepository, MembreRepository $membreRepository, SessionInterface $session, DesactiveRepository $desactiveRepository): Response
    {
        $votes = $desactiveRepository->findBylien('Vote');
        $ouvertures = $desactiveRepository->findBylien('Candidature');
        $candidat = $candidatRepository->findCandidatAvote();
        $mainte = new DateTime("now");
        if (empty($candidat)) {
            $this->addFlash('warning', 'Le vote est terminer !');
            return $this->redirectToRoute('app_profile');
        }
        if (!empty($votes)) {
            $debut = $votes[count($votes) - 1]->getDebut();
            $fin = $votes[count($votes) - 1]->getFin();
            if ($fin > $mainte) {
                $activ = 1;
            } else {
                $activ = 0;
            }
        } else {
            $activ = 0;
        }
        if (!empty($ouvertures)) {
            $debut = $ouvertures[count($ouvertures) - 1]->getDebut();
            $fin = $ouvertures[count($ouvertures) - 1]->getFin();
            if ($fin > $mainte) {
                $activeet = 1;
            } else {
                $activeet = 0;
            }
        } else {
            $activeet = 0;
        }
        $votante = $votantRepository->findAll();
        $membre = $membreRepository->findAll();
        $votant = new Votant();
        $form = $this->createForm(VotantType::class, $votant);
        $form->handleRequest($request);
        $matriculeUser = $this->getUser()->getMatricule();
        if ($this->getUser()) {
            if ($matriculeUser == null) {
                $this->addFlash('danger', 'Vous ne pouvez pas Voter car vous être administrateur!');
                return $this->redirectToRoute('app_profile');
            }
            $matriculeEntrer = $matriculeUser;
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
            if ($peut != null) {
                $this->addFlash('warning', 'Vous avez vote !');
                return $this->redirectToRoute('app_profile');
            }

            $vote = $session->get('vote');
            $vote = $matriculeEntrer;
            $session->set('vote', $vote);


            return $this->render('profile/pagedevoteUser.html.twig', [
                'candidats' => $candidatRepository->findCandidatAvote(),
                'matriculeEntrer' => $matriculeEntrer,
                'votes' => $votes,
                'activeet' => $activeet,
                'activ' => $activ,
                'votant' => $votant,
                'ouvertures' => $ouvertures,
            ]);
        } else {
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
                    return $this->redirectToRoute('app_home');
                }
                if ($matriculeEntrer != $peutMembre) {
                    $this->addFlash('warning', 'Votre matricule est invalid !');
                    return $this->redirectToRoute('app_home');
                }
                if ($peut != null) {
                    $this->addFlash('warning', 'Vous avez vote !');
                    return $this->redirectToRoute('app_home');
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
                'votes' => $votes,
                'activeet' => $activeet,
                'activ' => $activ,
                'votant' => $votant,
                'ouvertures' => $ouvertures,
                'form' => $form->createView(),
            ]);
        }
    }
    #[Route('/choix/{id}', name: 'candidat_choix', methods: ['GET'])]
    public function choix(Candidat $candidat, VotantRepository $votantRepository, SessionInterface $session, CandidatRepository $candidatRepository,): Response
    {

        $vote = $session->get('vote');
        $votante = $votantRepository->findAll();
        $matriculeEntrer = $vote;
        $matriculeUser = $this->getUser()->getMatricule();
        if (empty($vote)) {
            $this->addFlash('info', 'Vous devrez vous entrez votre matricule avant de passer au vote !');
            return $this->redirectToRoute('app_home');
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
                if ($this->getUser()) {
                    if ($matriculeUser == null) {
                        return $this->redirectToRoute('app_home');
                    }
                    return $this->redirectToRoute('app_profile');
                } else {
                    return $this->redirectToRoute('app_home');
                }
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
            if ($this->getUser()) {
                if ($matriculeUser == null) {
                    return $this->redirectToRoute('app_home');
                }
                return $this->redirectToRoute('app_profile');
            } else {
                return $this->redirectToRoute('app_home');
            }
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
                if ($this->getUser()) {
                    if ($matriculeUser == null) {
                        return $this->redirectToRoute('app_home');
                    }
                    return $this->redirectToRoute('app_profile');
                } else {
                    return $this->redirectToRoute('app_home');
                }
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
            if ($this->getUser()) {
                if ($matriculeUser == null) {
                    return $this->redirectToRoute('app_home');
                }
                return $this->redirectToRoute('app_profile');
            } else {
                return $this->redirectToRoute('app_home');
            }
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
    #[Route('/etat/fin', name: 'candidat_fin_plublic', methods: ['GET'])]
    public function etatfin(CandidatRepository $candidatRepository): Response
    {
        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));
        $tour = "1er Tour";
        $candidat = $candidatRepository->findCandidatAnnee($annee);
        if (empty($candidat)) {
            return $this->redirectToRoute('candidature_index');
        }
        for ($i = 0; $i < count($candidat); $i++) {

            if ($candidat[$i]->getTour2() == "2eme Tour") {
                $tour = $candidat[$i]->getTour2();
            }
        }
        if ($tour == "2eme Tour") {
            $candidats = $candidatRepository->findCandidatAnneeTour2($tour);
            $fin = $candidats;
            for ($i = 0; $i < count($fin); $i++) {
                $entityManager = $this->getDoctrine()->getManager();
                if ($fin[$i]->getTour2() == "2eme Tour") {
                    $fin[$i]->setVuePublic(($fin[$i]->getVuePublic() == 0) ? true : false);
                } else {
                    # code...
                }
                $fin[$i]->setEtat(true);
                $entityManager->flush();
            }
        } else {
            $candidats = $candidatRepository->findCandidatAnnee($annee);
            $fin = $candidats;
            for ($i = 0; $i < count($fin); $i++) {
                $entityManager = $this->getDoctrine()->getManager();
                $fin[$i]->setVuePublic(($fin[$i]->getVuePublic() == 0) ? true : false);
                $fin[$i]->setEtat(true);
                $entityManager->flush();
            }
        }


        return $this->redirectToRoute('candidat_resultat');
    }
    #[Route('/etat/public', name: 'candidat_etatpublic', methods: ['GET'])]
    public function etatpublic(CandidatRepository $candidatRepository, DesactiveRepository $desactiveRepository, PresidentRepository $presidentRepository, PostAjeutchimRepository $postAjeutchimRepository): Response
    {
        $post = $postAjeutchimRepository->pesident('Président');

        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));
        $vinqueur = $candidatRepository->findCandidatPlusPoint($annee);
        $president = new President();
        $bureau = new Bureau();
        $actuelpresident = $presidentRepository->findEncour(false);
        if (count($actuelpresident) == null) {
            for ($i = 0; $i < count($vinqueur); $i++) {
                $entityManager = $this->getDoctrine()->getManager();
                $president->setUser($this->getUser());
                $president->setMembre($vinqueur[$i]->getMembre());
                $president->setDebutedAt($jouj);
                $president->setEtat(0);
                $president->setUser($this->getUser());
                $entityManager->persist($president);
                $bureau->setMembre($vinqueur[$i]->getMembre());
                for ($p = 0; $p < count($post); $p++) {
                    $bureau->setPostAjeutchim($post[$p]);
                }
                $bureau->setPresident($president);
                $bureau->setEtat(0);
                $entityManager->persist($bureau);
                $entityManager->flush();
            }
        }
        $desac = $desactiveRepository->findAll();
        for ($i = 0; $i < count($desac); $i++) {
            $entityManager = $this->getDoctrine()->getManager();
            if ($desac[$i]->getNom() == "Vote") {
                if ($desac[$i]->getEtat() == true) {
                    $this->addFlash('info', 'Veuillez desactivez le lien de vote');
                    return $this->redirectToRoute('desactive_index');
                }
            }
            if ($desac[$i]->getNom() == "Candidature") {
                if ($desac[$i]->getEtat() == true) {
                    $this->addFlash('info', 'Veuillez desactivez le lien de candidature');
                    return $this->redirectToRoute('desactive_index');
                }
            }
            $entityManager->flush();
        }
        $fin1 = $candidatRepository->findAll();
        for ($i = 0; $i < count($fin1); $i++) {
            $entityManager = $this->getDoctrine()->getManager();
            $fin1[$i]->setFin(($fin1[$i]->getFin() == 0) ? true : false);
            $fin1[$i]->setEtat(true);
            $entityManager->flush();
        }
        $fin = $candidatRepository->findAll();
        $toure = 1;
        for ($tou = 0; $tou < count($fin); $tou++) {
            if (!empty($fin[$tou]->getTour2())) {
                $toure = 2;
            }
        }
        if ($toure == 2) {
            for ($i = 0; $i < count($fin); $i++) {
                $entityManager = $this->getDoctrine()->getManager();
                $nmbreToure1 = $fin[$i]->getTour1();
                $nmbreToure2 = $fin[$i]->getTour2();
                $fin[$i]->setVuePublic(false);
                if (!empty($nmbreToure2) && !empty($nmbreToure1)) {
                    $fin[$i]->setVuePublic(($fin[$i]->getVuePublic() == 0) ? true : false);
                } elseif (empty($nmbreToure2) && !empty($nmbreToure1)) {
                    $fin[$i]->setVuePublic(false);
                }
            }
            $entityManager->flush();
        } else {
            for ($i = 0; $i < count($fin); $i++) {
                $entityManager = $this->getDoctrine()->getManager();
                $nmbreToure1 = $fin[$i]->getTour1();
                $nmbreToure2 = $fin[$i]->getTour2();
                $fin[$i]->setVuePublic(($fin[$i]->getVuePublic() == 0) ? true : false);
            }
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