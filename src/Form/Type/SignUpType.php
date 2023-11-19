<?php

namespace App\Form\Type;

use App\Validator\Constraints\ValidPassword;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class SignUpType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    #[NoReturn]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', Type\TextType::class, [
                'label' => 'firstName',
                'label_attr' => [
                    'class' => 'sr-only d-none',
                ],
                'constraints' => [new NotBlank()],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'firstName',
                ],
            ])
            ->add('lastName', Type\TextType::class, [
                'label' => 'lastName',
                'label_attr' => [
                    'class' => 'sr-only d-none',
                ],
                'constraints' => [new NotBlank()],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'lastName',
                ],
            ])
            ->add('email', Type\EmailType::class, [
                'label' => 'email',
                'label_attr' => [
                    'class' => 'sr-only d-none',
                ],
                'constraints' => [new NotBlank(), new Email()],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'email',
                ],
            ])
            ->add('password', Type\RepeatedType::class, [
                'type' => Type\PasswordType::class,
                'invalid_message' => 'The two values should be equal.',
                'first_options' => [
                    'label' => 'password.firstOptions',
                    'label_attr' => [
                        'class' => 'sr-only d-none',
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new ValidPassword(),
                    ],
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'password.firstOptions',
                    ],
                ],
                'second_options' => [
                    'label' => 'password.secondOptions',
                    'label_attr' => [
                        'class' => 'sr-only d-none',
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new ValidPassword(),
                    ],
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'password.secondOptions',
                    ],
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
            'translation_domain' => 'SignUp',
            'csrf_token_id' => 'sign_up',
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}