<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Clip;
use App\Model\SearchArtistModel;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchArtistFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'form-control w-50',
                    'placeholder' => 'nom',
                    'title' => 'Recherche par nom d\'artiste'
                ]
            ])
            ->add('clip', EntityType::class, [
                'required' => false,
                'class' => Clip::class,
                'label' => false,
                'query_builder' => function (EntityRepository $e) {
                    return $e->createQueryBuilder('a')
                        ->orderBy('a.titre');
                },
                'choice_label' => 'titre',
                'attr' => [
                    'hidden' => true
                ]
            ])
            ->add('departement', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'form-control w-25',
                    'placeholder' => 'dpt',
                    'title' => 'Recherche par numéro de département'
                ]
            ])
            ->add('album', EntityType::class, [
                'required' => false,
                'class' => Album::class,
                'label' => false,
                'query_builder' => function (EntityRepository $e) {
                    return $e->createQueryBuilder('a')
                            ->orderBy('a.nom');
                },
                'choice_label' => 'nom',
                'attr' => [
                    'hidden' => true
                ]
            ])
            ->add('search', SubmitType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'btn btn-lg btn-outline-success my-2 my-sm-0 fas fa-search'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchArtistModel::class
        ]);
    }
}
