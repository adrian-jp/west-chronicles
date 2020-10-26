<?php

namespace App\Form;

use App\Entity\Lieu;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adresse', TextType::class, [
                'label' => 'Numéro de rue et rue'
            ])
            ->add('codePostal', TextType::class, [
                'label' => 'Code Postal'
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville'
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom de la salle ou du lieu'
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
            'data_class' => Lieu::class,
        ]);
    }
}
