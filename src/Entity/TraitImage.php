<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Trait TraitImage
 * @package App\Entity
 */
trait TraitImage {

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Expose()
     */
    private $image;

    /**
     * @return String
     */
    public function getImage(): ?String
    {
        return $this->image;
    }

    /**
     * @param String $image
     */
    public function setImage(?String $image): void
    {
        $this->image = $image;
    }
}