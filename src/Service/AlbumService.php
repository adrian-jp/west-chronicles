<?php


namespace App\Service;


use App\Entity\Album;
use Doctrine\ORM\EntityManagerInterface;

class AlbumService
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;


    /**
     * AlbumService constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Album $album
     */
    public function addOrEdit(Album $album) {
        $this->manager->persist($album);
        $this->manager->flush();
    }
}