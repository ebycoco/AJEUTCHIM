<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\AproposRepository;
use App\Repository\ArticleRepository;
use App\Repository\DesactiveRepository;
use App\Repository\EvenementRealiserRepository;
use App\Repository\FlashRepository;
use App\Repository\ImageAccueilRepository;
use App\Repository\PresidentRepository;
use App\Repository\VideoRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(Request $request, ImageAccueilRepository $imageAccueilRepository, EvenementRealiserRepository $evenementRealiserRepository, FlashRepository $flashRepository, PresidentRepository $presidentRepository, VideoRepository $videoRepository, ArticleRepository $articleRepository, AproposRepository $aproposRepository,DesactiveRepository $desactiveRepository): Response
    {
        $desactive=$desactiveRepository->findAll();
        $mainte=new DateTime("now");  
       if (!empty($desactive)) {
        $debut = $desactive[count($desactive) - 1]->getDebut();
        $fin = $desactive[count($desactive) - 1]->getFin();  
        if ($fin > $mainte ) { 
            $activee = 1;
        } else { 
            $activee = 0;
        }
       }else {
        $activee = 0;
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
        return $this->render('home/index.html.twig', [
            'image_accueils' => $imageAccueilRepository->findAll(),
            'evenement_realisers' => $evenementRealiserRepository->findAll(),
            'flashes' => $flashRepository->findAll(),
            'presidents' => $presidentRepository->findAll(),
            'videos' => $videoRepository->findAll(),
            'articles' => $articleRepository->findAll(),
            'apropos' => $aproposRepository->findAll(),
            'desactiver' => $desactiveRepository->findAll(),
            'articles' => $articles,
            'pages' => $pages,
            'page' => $page,
            'limit' => $limit,
            'range' => $range,
            'activee'=>$activee,
        ]);
    }
}