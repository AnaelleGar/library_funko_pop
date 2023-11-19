<?php

namespace App\Form\Type;

use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class LoginType.
 */
class LoginType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    #[NoReturn]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('_username', Type\EmailType::class, [
                'label' => '_email',
                'attr' => [
                    'placeholder' => '_email',
                ],
            ])
            ->add('_password', Type\PasswordType::class, [
                'label' => '_password',
                'attr' => [
                    'placeholder' => '_password',
                ],
            ])
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    #[NoReturn]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'SignIn',
            'csrf_token_id' => 'authenticate',
        ]);
    }
}
