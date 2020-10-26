<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Clip;
use App\Entity\Genre;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClipFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
        'label' => 'Titre'
    ])
            ->add('url', TextType::class, [
                'label' => 'Copiez le lien YouTube'
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
            ->add('genre', EntityType::class, [
                'class' => Genre::class,
                'query_builder' => function(EntityRepository $e) {
                    return $e->createQueryBuilder('g')
                            ->orderBy('g.nom', 'ASC');
                },
                'choice_label' => 'nom',
                'placeholder' => 'Choisir style musical'
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
            'data_class' => Clip::class,
        ]);
    }
}
