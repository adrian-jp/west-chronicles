<?php

namespace App\Form;

use App\Entity\Artist;
use App\Model\TypeFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class ArtistFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'artiste ou du groupe'
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
                    'class' => 'custom-file-input-button btn btn-info btn-sm',
                    'accept' => TypeFile::MIME_TYPE_IMAGE,
                    'onchange' => 'setNameIntoInput(this); previewImage(this);',
                    'onload' => 'setNameIntoInput(this)',
                    'name' => 'uploadPhoto'
                ]
            ])
            ->add('departement', ChoiceType::class, [
                'choices' => Artist::ALL_DEPT,
                'placeholder' => 'Sélectionner un département',
                'choice_label' => function ($value) {
        return $value;
    }
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
            'data_class' => Artist::class,
        ]);
    }
}
