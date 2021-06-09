<?php

namespace App\Controller;

use App\Entity\Candidature;
use DateTime;
use App\Entity\User;
use App\Entity\Votant;
use App\Entity\Depense;
use App\Form\CandidatureType;
use App\Form\VotantType;
use App\Form\DepenseType;
use App\Repository\UserRepository;
use App\Form\EditUserConnecterType;
use App\Repository\BureauRepository;
use App\Repository\MembreRepository;
use App\Repository\VotantRepository;
use App\Repository\DepenseRepository;
use App\Repository\CandidatRepository;
use App\Repository\DesactiveRepository;
use App\Repository\CotisationRepository;
use App\Repository\CandidatureRepository;
use App\Repository\PresidentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/profile/utilisateur")
 */
class ProfileController extends AbstractController
{
    #[Route('/', name: 'app_profile')]
    public function index(Request $request, DesactiveRepository $desactiveRepository, CotisationRepository $cotisationRepository, VotantRepository $votantRepository, SessionInterface $session, MembreRepository $membreRepository, CandidatRepository $candidatRepository, BureauRepository $bureauRepository, PresidentRepository $presidentRepository): Response
    {
        $noubeauPresident = $presidentRepository->findEncour(0);
        $bureau = $bureauRepository->findMembreBureau($noubeauPresident);
        $emaildefault = $this->getUser()->getEmail();
        $motemaildefault = substr($emaildefault, 0, 9);
        if ($motemaildefault === "Ajeutchim") {
            $this->addFlash('warning', 'Vous devrez changez e-mail pour faire des modifications');
        }
        $candidat = $candidatRepository->findCandidatAvote();
        $votante = $votantRepository->findAll();
        $membre = $membreRepository->findAll();
        $mainte = new DateTime("now");
        $ouvertures = $desactiveRepository->findBylien('Candidature');
        $votes = $desactiveRepository->findBylien('Vote');
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
        $membre = $this->getUser()->getid();
        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));
        $cotisations = $cotisationRepository->findOnMembre($membre);
        $votant = new Votant();
        $form1 = $this->createForm(VotantType::class, $votant);
        $form1->handleRequest($request);
        if ($form1->isSubmitted() && $form1->isValid()) {
            $matriculeEntrer = $form1->get('matricule')->getData();
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
                if (empty($candidat)) {
                    $this->addFlash('warning', 'Le vote est terminer !');
                    return $this->redirectToRoute('app_home');
                }
                if ($peutMembre == null) {
                    $this->addFlash('warning', 'Votre matricule est introuvable !');
                    return $this->redirectToRoute('app_home');
                }
                if ($matriculeEntrer != $peutMembre) {
                    $this->addFlash('warning', 'Votre matricule est invalid !');
                    return $this->redirectToRoute('candidat_vote');
                }
                if ($peut != null) {
                    $this->addFlash('warning', 'Vous avez vote !');
                    return $this->redirectToRoute('app_home');
                }

                $vote = $session->get('vote');
                $vote = $matriculeEntrer;
                $session->set('vote', $vote);
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
                } elseif ($tour == "1er Tour") {
                    $candidats = $candidatRepository->findCandidatAnnee($annee);
                }
                return $this->render('candidat/pagedevote.html.twig', [
                    'candidats' => $candidats,
                    'matriculeEntrer' => $matriculeEntrer,
                ]);
            } elseif ($secondTour = "oui") {
                $peut = null;
                $fois = 0;
                for ($i = 0; $i < count($votante); $i++) {
                    $matricul = $votante[$i]->getMatricule();
                    if ($matriculeEntrer == $matricul) {
                        $peut = $matricul;
                        $fois = $fois + 1;
                    }
                }
                $peutMembre = null;
                for ($i = 0; $i < count($membre); $i++) {
                    $matricule = $membre[$i]->getReferenceAjeutchim();
                    if ($matriculeEntrer == $matricule) {
                        $peutMembre = $matricule;
                    }
                }
                if (empty($candidat)) {
                    $this->addFlash('warning', 'Le vote est terminer !');
                    return $this->redirectToRoute('app_home');
                }
                if ($peutMembre == null) {
                    $this->addFlash('warning', 'Votre matricule est introuvable !');
                    return $this->redirectToRoute('app_home');
                }
                if ($matriculeEntrer != $peutMembre) {
                    $this->addFlash('warning', 'Votre matricule est invalid !');
                    return $this->redirectToRoute('candidat_vote');
                }
                if ($fois > 1) {
                    $this->addFlash('warning', 'Vous avez vote !');
                    return $this->redirectToRoute('app_home');
                }
                $vote = $session->get('vote');
                $vote = $matriculeEntrer;
                $session->set('vote', $vote);
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
                } elseif ($tour == "1er Tour") {
                    $candidats = $candidatRepository->findCandidatAnnee($annee);
                }
                return $this->render('candidat/pagedevote.html.twig', [
                    'candidats' => $candidats,
                    'matriculeEntrer' => $matriculeEntrer,
                ]);
            } else {
                # code...
            }
        }

        return $this->render('profile/index.html.twig', [
            'cotisations' => $cotisations,
            'activeet' => $activeet,
            'ouvertures' => $ouvertures,
            'votes' => $votes,
            'activ' => $activ,
            'annee' => $annee,
            'bureau' => $bureau,
            'form1' => $form1->createView(),
        ]);
    }
    #[Route('/Ma-candidature', name: 'candidature_new', methods: ['GET', 'POST'])]
    public function candidature(Request $request, MembreRepository $membreRepository, CandidatureRepository $candidatureRepository, DesactiveRepository $desactiveRepository): Response
    {
        $listeCandidat = $candidatureRepository->findAll();
        $candidature = new Candidature();
        $form = $this->createForm(CandidatureType::class, $candidature);
        $form->handleRequest($request);
        $matriculeUser = $this->getUser()->getMatricule();

        $mainte = new DateTime("now");
        $ouvertures = $desactiveRepository->findBylien('Candidature');
        $votes = $desactiveRepository->findBylien('Vote');
        $membre = $this->getUser();
        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));
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
            'activeet' => $activeet,
            'ouvertures' => $ouvertures,
            'votes' => $votes,
            'activ' => $activ,
            'annee' => $annee,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/Mes-Cotisation', name: 'app_mescotisation')]
    public function mescotisation(Request $request, DesactiveRepository $desactiveRepository, CotisationRepository $cotisationRepository, VotantRepository $votantRepository, SessionInterface $session, MembreRepository $membreRepository, CandidatRepository $candidatRepository, PaginatorInterface $paginator): Response
    {
        $mainte = new DateTime("now");
        $ouvertures = $desactiveRepository->findBylien('Candidature');
        $votes = $desactiveRepository->findBylien('Vote');
        $membre = $this->getUser()->getid();
        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));
        $data = $cotisationRepository->findOnMembre($membre);
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
        $cotisations = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            12
        );
        return $this->render('profile/mes_cotisation.html.twig', [
            'cotisations' => $cotisations,
            'activeet' => $activeet,
            'ouvertures' => $ouvertures,
            'votes' => $votes,
            'activ' => $activ,
            'annee' => $annee,
        ]);
    }
    /**
     * @Route("/nouvelle-projet", name="projet_new", methods={"GET","POST"})
     */
    public function projet(Request $request, DesactiveRepository $desactiveRepository): Response
    {
        $mainte = new DateTime("now");
        $ouvertures = $desactiveRepository->findBylien('Candidature');
        $votes = $desactiveRepository->findBylien('Vote');
        $membre = $this->getUser()->getid();
        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));
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
            $this->addFlash('success', 'Votre projet nous a été envoyer, une équipe va s\'en charger de traiter dans un bref delai !');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/projet.html.twig', [
            'depense' => $depense,
            'activeet' => $activeet,
            'ouvertures' => $ouvertures,
            'votes' => $votes,
            'activ' => $activ,
            'annee' => $annee,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/mes-projets", name="mes_projets", methods={"GET","POST"})
     */
    public function mesprojets(Request $request, DesactiveRepository $desactiveRepository, DepenseRepository $depenseRepository, PaginatorInterface $paginator): Response
    {
        $mainte = new DateTime("now");
        $ouvertures = $desactiveRepository->findBylien('Candidature');
        $membre = $this->getUser()->getid();
        $data = $depenseRepository->findOnMembre($membre);
        $votes = $desactiveRepository->findBylien('Vote');
        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));
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
        $depenses = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            12
        );
        return $this->render('profile/mes_projet.html.twig', [
            'depenses' => $depenses,
            'activeet' => $activeet,
            'ouvertures' => $ouvertures,
            'votes' => $votes,
            'activ' => $activ,
            'annee' => $annee,
        ]);
    }
    /**
     * @Route("/mes-projets/edit/{id}", name="depense_edit", methods={"GET","POST"})
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

        return $this->render('admin/depense/edit.html.twig', [
            'depense' => $depense,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/bureau", name="app_bureau", methods={"GET","POST"})
     */
    public function bureau(DesactiveRepository $desactiveRepository, BureauRepository $bureauRepository, PresidentRepository $presidentRepository): Response
    {
        $noubeauPresident = $presidentRepository->findEncour(0);
        $bureau = $bureauRepository->findMembreBureau($noubeauPresident);
        $mainte = new DateTime("now");
        $ouvertures = $desactiveRepository->findBylien('Candidature');
        $votes = $desactiveRepository->findBylien('Vote');
        $membre = $this->getUser();
        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));
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


        return $this->render('profile/bureau.html.twig', [
            'activeet' => $activeet,
            'ouvertures' => $ouvertures,
            'votes' => $votes,
            'activ' => $activ,
            'annee' => $annee,
            'bureau' => $bureau,
        ]);
    }

    /**
     * @Route("/mise-a-jour", name="user_profile")
     */
    public function userprofil(Request $request, DesactiveRepository $desactiveRepository, MembreRepository $membreRepository): Response
    {

        $IdPass = $this->getUser()->getId();
        $user = $this->getUser();
        $form = $this->createForm(EditUserConnecterType::class, $user);
        $mainte = new DateTime("now");
        $ouvertures = $desactiveRepository->findBylien('Candidature');
        $votes = $desactiveRepository->findBylien('Vote');
        $membre = $this->getUser();
        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));
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
        $emaildefault = $this->getUser()->getEmail();
        $motemaildefault = substr($emaildefault, 0, 9);
        if ($motemaildefault === "Ajeutchim") {
            $this->addFlash('warning', 'Vous devrez changez e-mail pour faire des modifications');
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $emailform = $form->get('email')->getData();
            $motemailentrer = substr($emailform, 0, 9);

            if ($motemailentrer === "Ajeutchim") {
                $this->addFlash('warning', 'Vous devrez changez e-mail');
                return $this->redirectToRoute('user_profile', ['id' => $IdPass]);
            } else {
                $entityManager = $this->getDoctrine()->getManager();
                $membreuser = $membreRepository->findAllmembreUser($this->getUser()->getMatricule());
                for ($m = 0; $m < count($membreuser); $m++) {
                    $membreuser[$m]->setNom($form->get('nom')->getData());
                    $membreuser[$m]->setPrenom($form->get('prenom')->getData());
                    $membreuser[$m]->setVille($form->get('ville')->getData());
                    $membreuser[$m]->setContact($form->get('contact')->getData());
                    $membreuser[$m]->setProfession($form->get('profession')->getData());
                    $membreuser[$m]->setEmail($form->get('email')->getData());
                }
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', 'Votre e-mail a été modifier avec!');
                return $this->redirectToRoute('user_profile', ['id' => $IdPass]);
            }
        }

        return $this->render('profile/profile.html.twig', [
            'userForm' => $form->createView(),
            'activeet' => $activeet,
            'ouvertures' => $ouvertures,
            'votes' => $votes,
            'activ' => $activ,
            'annee' => $annee,
        ]);
    }
}