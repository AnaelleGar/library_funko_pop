<?php

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\Movie;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Regex;

class MovieType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    #[NoReturn]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', Type\TextType::class, [
                'label' => 'label.title',
                'attr' => [
                    'class' => 'custom-class',
                    'placeholder' => 'placeholder.title',
                ],
            ])
            ->add('description', Type\TextareaType::class, [
                'label' => 'label.title',
                'attr' => [
                    'class' => 'custom-class',
                    'placeholder' => 'placeholder.title',
                ],
            ])
            ->add('poster', Type\FileType::class, [
                'label' => 'label.poster',
                'mapped' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/gif',
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'invalid.file',
                    ]),
                ],
            ])
            ->add('category', EntityType::class, [
                'label' => 'label.category',
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true,
            ]);
    }

    /**
     * {@inheritDoc}
     */
    #[NoReturn]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
            'translation_domain' => 'movie',
            'categories' => null,
        ]);
    }
}
