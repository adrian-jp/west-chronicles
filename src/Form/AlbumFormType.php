<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Artist;
use App\Model\TypeFile;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class AlbumFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Titre d\'album'
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
            ->add('image', FileType::class, [
                'required' => false,
                'label' => false,
                "data_class" => null,
                'constraints' => [
                    new Assert\File([
                        'maxSize' => TypeFile::MAX_SIZE
                    ])
                ],
                'attr' => [
                    'class' => 'custom-file-input-button btn btn-info btn-md',
                    'accept' => TypeFile::MIME_TYPE_IMAGE,
                    'onchange' => 'setNameIntoInput(this); previewImage(this);',
                    'onload' => 'setNameIntoInput(this)',
                    'name' => 'uploadPhoto'
                ]
            ])
            ->add('tracks', CollectionType::class, [
                'entry_type' => TrackAlbumFormType::class,
                'entry_options' => [
                  'label' => false
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'mapped' => false,
                'prototype' => true,
                'required' => false,
                'by_reference' => false,
                'delete_empty' => true,
                'attr' => [
                    'class' => 'album-tracks-collection float-left mb-2'
                ]
            ])
            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
        $builder->get('tracks')->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
        ]);
    }
}
