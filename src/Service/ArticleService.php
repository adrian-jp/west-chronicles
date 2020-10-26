<?php


namespace App\Service;


use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;

class ArticleService
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;


    /**
     * ArticleService constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function addOrEdit(Article $article, bool $ajout) {
        if($ajout) {
            $article->setDatePublication(new \DateTime('now'));
        }

        $this->manager->persist($article);
        $this->manager->flush();
        return $article;
    }
}