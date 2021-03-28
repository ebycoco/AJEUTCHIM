<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\DemandeAdhesion;
use App\Entity\Votant;
use App\Form\DemandeAdhesionType;
use App\Form\VotantType;
use App\Repository\AproposRepository;
use App\Repository\ArticleRepository;
use App\Repository\BureauRepository;
use App\Repository\CandidatRepository;
use App\Repository\DesactiveRepository;
use App\Repository\EvenementRealiserRepository;
use App\Repository\FlashRepository;
use App\Repository\ImageAccueilRepository;
use App\Repository\MembreRepository;
use App\Repository\PresidentRepository;
use App\Repository\VideoRepository;
use App\Repository\VotantRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(Request $request, ImageAccueilRepository $imageAccueilRepository, EvenementRealiserRepository $evenementRealiserRepository, FlashRepository $flashRepository, PresidentRepository $presidentRepository, VideoRepository $videoRepository, ArticleRepository $articleRepository, AproposRepository $aproposRepository, DesactiveRepository $desactiveRepository, CandidatRepository $candidatRepository, VotantRepository $votantRepository, SessionInterface $session, MembreRepository $membreRepository, BureauRepository $bureauRepository): Response
    {

        $desactive = $desactiveRepository->findBylien('Lien');
        $ouvertures = $desactiveRepository->findBylien('Candidature');
        $votes = $desactiveRepository->findBylien('Vote');
        $mainte = new DateTime("now");
        if (!empty($desactive)) {
            $debut = $desactive[count($desactive) - 1]->getDebut();
            $fin = $desactive[count($desactive) - 1]->getFin();
            if ($fin > $mainte) {
                $activee = 1;
            } else {
                $activee = 0;
            }
        } else {
            $activee = 0;
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

        $limit = $request->get("limit", 4);
        $page = $request->get("page", 1);
        /**@var Paginator $articles */
        $articles = $this->getDoctrine()->getRepository(Article::class)->findGetPaginatedPosts(
            $page,
            $limit
        );
        $pages = ceil($articles->count() / $limit);
        $range = range(
            max($page - 2, 1),
            min($page + 2, $pages)
        );

        $candidat = $candidatRepository->findCandidatAvote();
        $votante = $votantRepository->findAll();
        $membre = $membreRepository->findAll();
        $presidentactuelle = $presidentRepository->findEncour(0);
        $bureaux = $bureauRepository->findMembreBureau($presidentactuelle);
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
                return $this->render('candidat/pagedevote.html.twig', [
                    'candidats' => $candidatRepository->findCandidatAvote(),
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
                return $this->render('candidat/pagedevote.html.twig', [
                    'candidats' => $candidatRepository->findCandidatAvote(),
                    'matriculeEntrer' => $matriculeEntrer,
                ]);
            } else {
                # code...
            }
        }

        $demandeAdhesion = new DemandeAdhesion();
        $form2 = $this->createForm(DemandeAdhesionType::class, $demandeAdhesion);
        $form2->handleRequest($request);

        if ($form2->isSubmitted() && $form2->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($demandeAdhesion);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }
        return $this->render('home/index.html.twig', [
            'image_accueils' => $imageAccueilRepository->findAll(),
            'evenement_realisers' => $evenementRealiserRepository->findAll(),
            'flashes' => $flashRepository->findAll(),
            'presidents' => $presidentRepository->findAll(),
            'videos' => $videoRepository->findAll(),
            'articles' => $articleRepository->findAll(),
            'apropos' => $aproposRepository->findAll(),
            'desactiver' => $desactive,
            'ouvertures' => $ouvertures,
            'votes' => $votes,
            'candidats' => $candidatRepository->findAll(),
            'bureaux' => $bureaux,
            'articles' => $articles,
            'pages' => $pages,
            'page' => $page,
            'limit' => $limit,
            'range' => $range,
            'activee' => $activee,
            'activeet' => $activeet,
            'activ' => $activ,
            'form1' => $form1->createView(),
            'form2' => $form2->createView(),
        ]);
    }
    /**
     * Undocumented function
     *@Route("/organigramme", name="app_organi")
     * @return Response
     */
    public function organi(): Response
    {
        return $this->render('home/history.html.twig');
    }
}