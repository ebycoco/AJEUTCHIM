<?php

namespace App\Controller;

use App\Entity\Depense;
use App\Entity\User;
use DateTime;
use App\Entity\Votant;
use App\Form\DepenseType;
use App\Form\EditUserConnecterType;
use App\Form\VotantType;
use App\Repository\MembreRepository;
use App\Repository\VotantRepository;
use App\Repository\CandidatRepository;
use App\Repository\DesactiveRepository;
use App\Repository\CotisationRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/profile")
 */
class ProfileController extends AbstractController
{
    #[Route('/', name: 'app_profile')]
    public function index(Request $request, DesactiveRepository $desactiveRepository, CotisationRepository $cotisationRepository, VotantRepository $votantRepository, SessionInterface $session, MembreRepository $membreRepository, CandidatRepository $candidatRepository): Response
    {
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
            'form1' => $form1->createView(),
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
     * @Route("/new", name="projet_new", methods={"GET","POST"})
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
            $this->addFlash('success', 'Vous projet nous a été envoyer une équipe va s\'en charger de traiter dans un bref delai !');
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
     * @Route("/utilisateur/profile/{id}", name="user_profile")
     */
    public function userprofil(User $user, Request $request, DesactiveRepository $desactiveRepository, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder,): Response
    {
        $IdPass = $this->getUser()->getId();
        $form = $this->createForm(EditUserConnecterType::class, $user);
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
        // if ($this->getUser()->getPassword(
        //     $passwordEncoder->encodePassword(
        //         $this->getUser(),
        //         "123456"
        //     )
        // ) == $this->getUser()->getPassword()) {
        //     $this->addFlash('warning', 'Vous devrez changez e-mail d\'abord!');
        // }
        $emaildefault = $this->getUser()->getEmail();
        $motemaildefault = substr($emaildefault, 0, 9);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $emailform = $form->get('email')->getData();
            $motemailentrer = substr($emailform, 0, 9);
            if ($motemaildefault === $motemailentrer) {
                $this->addFlash('warning', 'Vous devrez changez e-mail');
                return $this->redirectToRoute('user_profile', ['id' => $IdPass]);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Ajouter avec success !');
            return $this->redirectToRoute('user_profile', ['id' => $IdPass]);
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