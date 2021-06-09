<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Contact;
use App\Entity\DemandeAdhesion;
use App\Entity\Votant;
use App\Form\CommentType;
use App\Form\ContactType;
use App\Form\DemandeAdhesionType;
use App\Form\VotantType;
use App\Repository\AproposRepository;
use App\Repository\ArticleRepository;
use App\Repository\BureauRepository;
use App\Repository\CandidatRepository;
use App\Repository\CommentRepository;
use App\Repository\DesactiveRepository;
use App\Repository\EvenementRealiserRepository;
use App\Repository\FlashRepository;
use App\Repository\FonctionAjeutchimRepository;
use App\Repository\GaleryRepository;
use App\Repository\ImageAccueilRepository;
use App\Repository\MembreRepository;
use App\Repository\PresidentRepository;
use App\Repository\UserRepository;
use App\Repository\VideoRepository;
use App\Repository\VillageRepository;
use App\Repository\VotantRepository;
use App\Service\CommentaireService;
use App\Service\ContactService;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods={"GET","POST"})
     */
    public function index(
        Request $request,
        ImageAccueilRepository $imageAccueilRepository,
        EvenementRealiserRepository $evenementRealiserRepository,
        FlashRepository $flashRepository,
        PresidentRepository $presidentRepository,
        VideoRepository $videoRepository,
        ArticleRepository $articleRepository,
        AproposRepository $aproposRepository,
        DesactiveRepository $desactiveRepository,
        CandidatRepository $candidatRepository,
        VotantRepository $votantRepository,
        SessionInterface $session,
        MembreRepository $membreRepository,
        BureauRepository $bureauRepository,
        UserRepository $userRepository,
        GaleryRepository $galeryRepository,
        ContactService $contactService,
    ): Response {

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

        $demandeAdhesion = new DemandeAdhesion();
        $form2 = $this->createForm(DemandeAdhesionType::class, $demandeAdhesion);
        $form2->handleRequest($request);

        if ($form2->isSubmitted() && $form2->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($demandeAdhesion);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        $contact = new Contact();
        $form3 = $this->createForm(ContactType::class, $contact);
        $form3->handleRequest($request);
        if ($form3->isSubmitted() && $form3->isValid()) {
            $contact = $form3->getData();
            $contactService->persistContact($contact);
            return $this->redirectToRoute('app_home');
        }
        return $this->render('home/index.html.twig', [
            'image_accueils' => $imageAccueilRepository->findAll(),
            'evenement_realisers' => $evenementRealiserRepository->findAll(),
            'flashes' => $flashRepository->findAll(),
            'presidents' => $presidentRepository->findAll(),
            'videos' => $videoRepository->lasteVideo(),
            'articles' => $articleRepository->dernierArticle(),
            'apropos' => $aproposRepository->findAll(),
            'desactiver' => $desactive,
            'ouvertures' => $ouvertures,
            'votes' => $votes,
            'candidats' => $candidatRepository->resultatPublic(),
            'users' => $userRepository->findAll(),
            'galeries' => $galeryRepository->findAll(),
            'bureaux' => $bureaux,
            'activee' => $activee,
            'activeet' => $activeet,
            'activ' => $activ,
            'form1' => $form1->createView(),
            'form2' => $form2->createView(),
            'form3' => $form3->createView(),
        ]);
    }
    /**
     * Undocumented function
     *@Route("/fonction", name="app_organi")
     * @return Response
     */
    public function fonction(
        FonctionAjeutchimRepository $fonctionAjeutchimRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $data = $fonctionAjeutchimRepository->findAll();
        $fonctions = $paginator->paginate($data, $request->query->getint('page', 1), 6);
        return $this->render('fonction_ajeutchim/fonction.html.twig', [
            'fonctions' => $fonctions,
        ]);
    }
    /**
     * @Route("/Liste-galery", name="galery_liste", methods={"GET"})
     */
    public function galery(
        GaleryRepository $galeryRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $data = $galeryRepository->findAll();
        $galeries = $paginator->paginate($data, $request->query->getint('page', 1), 6);
        return $this->render('galery/liste.html.twig', [
            'galeries' => $galeries,
        ]);
    }
    /**
     * @Route("/Liste-article", name="article_liste", methods={"GET"})
     */
    public function article(
        ArticleRepository $articleRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $data = $articleRepository->articleActive();
        $articles = $paginator->paginate($data, $request->query->getint('page', 1), 12);
        return $this->render('article/liste.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/article/{slug}", name="article_detail", methods={"GET","POST"})
     */
    public function show(
        Article $article,
        CommentRepository $commentRepository,
        CommentaireService $commentaireService,
        Request $request,
    ): Response {
        $commentaires = $commentRepository->findCommentaire($article);
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $commentaireService->persistCommentaire($comment, $article);
            return $this->redirectToRoute('article_detail', ['slug' => $article->getSlug()]);
        }
        return $this->render('article/detailarticle.html.twig', [
            'article' => $article,
            'commentaires' => $commentaires,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/Liste-video", name="video_liste", methods={"GET"})
     */
    public function video(
        VideoRepository $videoRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $data = $videoRepository->findAll();
        $videos = $paginator->paginate($data, $request->query->getint('page', 1), 12);
        return $this->render('video/listevideo.html.twig', [
            'videos' => $videos,
        ]);
    }

    /**
     * @Route("/village", name="village_detail", methods={"GET"})
     */
    public function village(VillageRepository $villageRepository): Response
    {
        return $this->render('village/detail.html.twig', [
            'villages' => $villageRepository->findAll(),
        ]);
    }
    /**
     * @Route("/apropos", name="apropos_detail", methods={"GET"})
     */
    public function apropos(AproposRepository $aproposRepository): Response
    {
        return $this->render('apropos/detail.html.twig', [
            'apropos' => $aproposRepository->findAll(),
        ]);
    }
}