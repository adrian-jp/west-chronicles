<?php
/**
 * Created by IntelliJ IDEA.
 * User: ajossepa
 * Date: 19/12/2019
 * Time: 09:11
 * @author Adrian JOSSE-PAUPION
 */

namespace App\EventListener;
use App\Entity\Administration\UserComplementInfo;
use App\Model\TypeFile;
use App\Service\FileUploaderService;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileListener
 * @package App\EventListener
 * @author Adrian JOSSE-PAUPION
 */
class FileListener
{

    const CORRESPONDANCE_DOCUMENT_CLASS_TYPE_FILE = [];
    /**
     * @var FileUploaderService
     * @author Adrian JOSSE-PAUPION
     */
    private $fileUploaderService;


    /**
     * FileListener constructor.
     * @param FileUploaderService $fileUploaderService
     */
    public function __construct(FileUploaderService $fileUploaderService)
    {
        $this->fileUploaderService = $fileUploaderService;
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (array_key_exists(get_class($entity), self::CORRESPONDANCE_DOCUMENT_CLASS_TYPE_FILE)) {
                // Le fichier est accessible par l'attribut Nom
                if ($filename = $entity->getNom()) {
                    $pathFile = $this->fileUploaderService->getUploadDirectory(self::CORRESPONDANCE_DOCUMENT_CLASS_TYPE_FILE[get_class($entity)]).'/'.$filename;
                    if (file_exists($pathFile)) {
                        $entity->setNom(new File($pathFile));
                    }
                }
        }

    }

}