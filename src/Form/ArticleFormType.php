<?php

namespace App\Form;

use App\Entity\Article;
use App\Model\TypeFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('auteur', TextType::class, [
                'label' => 'Auteur'
            ])
            ->add('datePublication', DateType::class, [
                'label' => 'Date de publication',
                'attr' => [
                    'class' => 'datepicker'
                ]
            ])
            ->add('published', CheckboxType::class, [
                'label' => 'Cocher si l\'article peut être publié',
                'required' => false
            ])
            ->add('contenu', TextareaType::class, [
                'label' => '',
                'attr' => [
                    'class' => 'contenu-article',
                    'placeholder' => 'Contenu de l\'article ici'
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
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
