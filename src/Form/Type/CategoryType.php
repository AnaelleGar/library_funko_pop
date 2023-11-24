<?php

namespace App\Form\Type;

use App\Entity\Category;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Regex;

class CategoryType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    #[NoReturn]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', Type\TextType::class, [
                'label' => 'label.title',
                'attr' => [
                    'class' => 'custom-class',
                    'placeholder' => 'placeholder.title',
                ],
                'constraints' => [
                    new Regex('/^[\p{L}-]+$/u')
                ],
            ])
        ;
    }

    /**
     * {@inheritDoc}
     */
    #[NoReturn]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'translation_domain' => 'category_movie',
            'csrf_token_id' => 'category_movie',
        ]);
    }
}