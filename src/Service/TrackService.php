<?php


namespace App\Service;


use App\Entity\Track;
use App\Model\TypeFile;
use App\Util\Utility;
use Doctrine\ORM\EntityManagerInterface;

class TrackService
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var Mp3File
     */
    private $file;
    /**
     * @var FileUploaderService
     */
    private $fileUploaderService;


    /**
     * TrackService constructor.
     * @param EntityManagerInterface $manager
     * @param Mp3File $file
     * @param FileUploaderService $fileUploaderService
     */
    public function __construct(EntityManagerInterface $manager, Mp3File $file, FileUploaderService $fileUploaderService)
    {
        $this->manager = $manager;
        $this->file = $file;
        $this->fileUploaderService = $fileUploaderService;
    }

    public function addOrEdit(Track $track, bool $ajout)
    {
        if ($ajout) {
            $name = $track->getTitre();
            try {
                $libelleCourt = Utility::generateLongToCourt($name);
                $track->setLibelleCourt($libelleCourt);
            } catch (\Exception $e) {
            }
        }
        $this->manager->persist($track);
        $this->manager->flush();

        return $track;
    }

    public function addOrEditInAlbum(Track $track)
    {

        $name = $track->getTitre();
        try {
            $libelleCourt = Utility::generateLongToCourt($name);
            $track->setLibelleCourt($libelleCourt);
        } catch (\Exception $e) {
        }

        //$this->manager->persist($track);
        //$this->manager->flush();

        return $track;
    }

    public function getDuration(string $file)
    {
        $mp3file = new Mp3File($file);
        $duration1 = $mp3file->getDurationEstimate();
        $duration2 = $mp3file->getDuration();

        return Mp3File::formatTime($duration2);

    }
}