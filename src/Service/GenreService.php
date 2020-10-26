<?php


namespace App\Service;


use App\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;

class GenreService
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;


    /**
     * GenreService constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function addGenre(Genre $genre) {
        $this->manager->persist($genre);
        $this->manager->flush();
        return $genre;
    }
}