<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Event;
use App\Entity\Lieu;
use App\Model\TypeFile;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class EventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'évènement :'
            ])
            ->add('date', DateType::class, [
                'label' => 'Date de l\'évènement',
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'd/m/Y',
                'attr' => [
                    'class' => 'datepicker-input'
                ]
            ])
            ->add('contenu', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'contenu-article'
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
                    'class' => 'event-artist-collection'
                ]
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'query_builder' => function(EntityRepository $e) {
                return $e->createQueryBuilder('lieu')
                        ->orderBy('lieu.nom', 'ASC');
                },
                'choice_label' => 'nom',
                'label' => 'Lieu ou salle de concert'
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
            'data_class' => Event::class,
        ]);
    }
}
