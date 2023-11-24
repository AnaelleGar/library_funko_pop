<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search', Type\TextType::class, [
                'required' => false,
            ])
            ->add('searchDate', Type\TextType::class, [
                'required' => false,
            ]);
    }
}