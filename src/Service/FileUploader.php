<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;
    private $directory;

    /**
     * FileUploader constructor.
     * @param $targetDirectory
     */
    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
        $this->directory = $targetDirectory;
    }

    /**
     * @param UploadedFile $file
     * @param string $typeFile
     * @return string
     */
    public function upload(UploadedFile $file, string $typeFile = '')
    {
        $fileName = $typeFile.'_'.md5(uniqid()).'.'.$file->guessExtension();
        $extension = $file->guessExtension();
        if (!$extension) {
            $extension = 'bin';
        }
        try {
            $file->move($this->getTargetDirectory(), rand(1,99999).'.'.$extension);
        } catch (FileException $e) {
            //TODO ... handle exception if something happens during file upload
        }
        return $fileName;
    }

    /**
     * Retourne le répertoire de dépôt des fichiers
     *
     * @return string
     */
    public function getTargetDirectory(): string
    {
        return $this->directory;
    }

    /**
     * Permet de modifier le répertoire de réception des fichiers mais en
     * restant dans répertoire d'upload
     *
     * @param string $newDirectory
     * @return FileUploader
     */
    public function setTargetDirectory($newDirectory) {
        $this->directory = $this->targetDirectory.'/'.$newDirectory;
        return $this;
    }

    /**
     * Réinitialise le répertoire d'upload
     *
     * @return FileUploader
     */
    public function reinitDirectory() {
        $this->directory = $this->targetDirectory;
        return $this;
    }
}
?>