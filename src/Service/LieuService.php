<?php


namespace App\Service;


use App\Entity\Lieu;
use Doctrine\ORM\EntityManagerInterface;

class LieuService
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * LieuService constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Lieu $lieu
     */
    public function addOrEdit(Lieu $lieu) {
        $this->manager->persist($lieu);
        $this->manager->flush();
    }
}