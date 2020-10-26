<?php


namespace App\Service;


use App\Entity\Commentaire;
use Doctrine\ORM\EntityManagerInterface;

class CommentaireService
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;


    /**
     * CommentaireService constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function addComment(Commentaire $commentaire) {
        $date = new \DateTime('now');
        $now = $date->format('d-m-Y');
        $commentaire->setDate($now);
        $this->manager->persist($commentaire);
        $this->manager->flush();

        return $commentaire;
    }
}