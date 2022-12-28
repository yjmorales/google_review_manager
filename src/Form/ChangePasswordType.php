<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', RepeatedType::class, [
                'type'            => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options'         => [
                    'attr' => [
                        'class'     => 'form-control',
                        'mapped'    => false,
                        'require'   => true,
                        'minlength' => 2,
                        'maxlength' => 255,
                    ]
                ],
                'required'        => true,
                'first_options'   => [
                    'label' => false,
                ],
                'second_options'  => [
                    'label' => false,
                ],
            ]);
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}