<?php


namespace App\Service;


use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;

class EventService
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;


    /**
     * EventService constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function addOrEdit(Event $event) {
        $this->manager->persist($event);
        $this->manager->flush();
    }
}