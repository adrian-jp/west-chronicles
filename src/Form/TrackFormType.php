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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class TrackFormType extends AbstractType
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
                'label' => ' ',
                'data_class' => null,
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'custom-file-input-button btn btn-info btn-md',
                    'accept' => TypeFile::MIME_FILE_TRACK,
                ],
                'constraints' => [
                    new File([
                        'maxSize' =>TypeFile::MAX_SIZE,
                        'mimeTypes' => TypeFile::EXTENSION_AUDIO

                    ])
                ]
            ])
            ->add('artists', CollectionType::class, [
                'entry_type' => EntityType::class,
                'required' => false,
                'entry_options' => [
                    'class' => Artist::class,
                    'label' => false,
                    'query_builder' => function(EntityRepository $e) {
                        return $e->createQueryBuilder('a')
                            ->orderBy('a.nom', 'ASC');
                    },
                    'choice_label' => function(Artist $artist) {
                        return $artist->getNom();
                    }
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'label' => 'Artiste(s)',
                'attr' => [
                    'class' => 'event-artist-collection float-left mb-2'
                ]
            ])
            ->add('genre', EntityType::class, [
                'class' => Genre::class,
                'query_builder' => function(EntityRepository $e) {
                    return $e->createQueryBuilder('g')
                        ->orderBy('g.nom', 'ASC');
                },
                'choice_label' => 'nom',
                'placeholder' => 'Choisir style musical'
            ])
            ->add('album', EntityType::class, [
                'required' => false,
                'class' => Album::class,
                'query_builder' => function(EntityRepository $e) {
                    return $e->createQueryBuilder('a')
                        ->orderBy('a.nom', 'ASC');
                },
                'choice_label' => 'nom',
                'by_reference' => false,
                'placeholder' => 'Choisir album'
            ])
            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Track::class,
        ]);
    }
}
