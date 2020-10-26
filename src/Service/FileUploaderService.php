<?php
/**
 * Created by IntelliJ IDEA.
 * User: ajossepa
 * Date: 19/12/2019
 * Time: 09:14
 * @author Adrian JOSSE-PAUPION
 */

namespace App\Service;
use App\Model\TypeFile;
use App\Util\Utility;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileUploaderService
 * @package App\Service
 * @author Adrian JOSSE-PAUPION
 */
class FileUploaderService
{
    /**
     * @var ParameterBagInterface
     * @author Adrian JOSSE-PAUPION
     */
    private $parameterBag;

    /**
     * FileUploaderService constructor.
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * Upload file
     *
     * @param UploadedFile $file
     * @param string $typeFile
     *
     * @param bool $filename
     * @return string
     *
     * @throws \Exception
     */
    public function upload(UploadedFile $file, string $typeFile, $filename =false)
    {
        if (in_array($typeFile, TypeFile::ALL_TYPE_FILE)) {
            if (!$filename){
                $filename = Utility::generateUniqFileName($file);
            }
            $uploadDirectory = $this->getUploadDirectory($typeFile);
            $file->move($uploadDirectory, $filename);
            return $filename;

        }
        throw new \Exception("Type de fichier non reconnu.");
    }

    /**
     * Récupère le répertoire d'creation selon le type de fichier
     *
     * @param string $typeFile
     *
     * @return string
     *
     * @author Adrian JOSSE-PAUPION
     * @throws \Exception
     */
    public function getUploadDirectory(string $typeFile)
    {
         switch ($typeFile) {
             case TypeFile::TYPE_FILE_TRACK :
                 return $this->parameterBag->get('upload_directory_tracks_audio');
                 break;
             case TypeFile::TYPE_FILE_CLIP :
                 return $this->parameterBag->get('upload_directory_clips_video');
                 break;
             case TypeFile::TYPE_FILE_PHOTO_ARTICLE :
                 return $this->parameterBag->get('upload_directory_article_photo');
                 break;
             case TypeFile::TYPE_FILE_PHOTO_ARTISTE :
                 return $this->parameterBag->get('upload_directory_artiste_photo');
                 break;
             default :
                 throw new \Exception("Aucun répertoire d'creation pour ce type de fichier");
         }
    }

}