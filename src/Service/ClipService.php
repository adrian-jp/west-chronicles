<?php


namespace App\Service;


use App\Entity\Clip;
use App\Util\Utility;
use Doctrine\ORM\EntityManagerInterface;

class ClipService
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * ClipService constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function addOrEditClip(Clip $clip, bool $ajout) {

        if ($ajout) {
            $name = $clip->getTitre();
            try {
                $libelleCourt = Utility::generateLongToCourt($name);
                $clip->setLibelleCourt($libelleCourt);
            } catch (\Exception $e) {
            }

        }
        $this->manager->persist($clip);
        $this->manager->flush();

        return $clip;
    }
}