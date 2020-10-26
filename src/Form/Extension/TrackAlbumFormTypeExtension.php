<?php


namespace App\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class TrackAlbumFormTypeExtension extends AbstractTypeExtension
{

    public function __call($name, $arguments)
    {
        // TODO: Implement @method iterable getExtendedTypes()
    }

    public static function getExtendedTypes() : iterable
    {
        return [FileType::class];
    }
}