<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Genre;
use App\Entity\Track;
use App\Model\TypeFile;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints as Assert;

class TrackAlbumFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Titre du son'
                ]
            ])
            ->add('length', TextType::class, [
                'label' => ' ',
                'attr' => [
                    'hidden' => true
                ]
            ])
            ->add('url', FileType::class, [
                'label' => false,
                'required' => false,
                'by_reference' => false,
                'attr' => [
                    'class' => 'custom-file-input-button btn btn-info btn-md mb-4',
                    'accept' => TypeFile::MIME_FILE_TRACK,
                    'multpile' => 'multiple'
                ],
                'constraints' => [
                    new Assert\File([
                        'maxSize' => TypeFile::MAX_SIZE,
                        'mimeTypes' => TypeFile::EXTENSION_AUDIO,
                    ])
                ]
            ])
            ->add('genre', EntityType::class, [
                'class' => Genre::class,
                'query_builder' => function (EntityRepository $e) {
                    return $e->createQueryBuilder('g')
                        ->orderBy('g.nom', 'ASC');
                },
                'choice_label' => 'nom',
                'placeholder' => 'Choisir style musical'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Track::class
        ]);
    }
}
