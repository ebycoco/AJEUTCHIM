<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CommentaireService
{
    private $manager;
    private $flash;


    /**
     *  __Construct method
     * @param $manager
     * @param $flash
     */
    public function __construct(EntityManagerInterface $manager, FlashBagInterface $flash)
    {
        $this->manager = $manager;
        $this->flash = $flash;
    }

    public function persistCommentaire(Comment $comment, Article $article): void
    {
        $comment->setIsPublished(false)
            ->setArticle($article);
        $this->manager->persist($comment);
        $this->manager->flush();
        $this->flash->add('success', 'Votre commentaire est bien envoyé, merci. Il sera publié après validation');
    }
}