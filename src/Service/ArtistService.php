<?php


namespace App\Service;


use App\Entity\Artist;
use Doctrine\ORM\EntityManagerInterface;

class ArtistService
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;


    /**
     * ArtistService constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function addOrEdit(Artist $artist) {
        $this->manager->persist($artist);
        $this->manager->flush();
    }
}