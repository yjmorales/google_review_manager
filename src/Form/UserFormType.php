<?php

namespace App\Form;

use App\Entity\User;
use App\Security\Role\RoleTypes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr'     => [
                    'class'       => 'form-control',
                    'placeholder' => 'Email',
                    'minlength'   => 2,
                    'maxlength'   => 255,
                    'help'        => 'This value acts as username to authenticate the user across the application.',
                ],
                'required' => true,
            ])
            ->add('roles', ChoiceType::class, [
                'choices'  => array_flip(RoleTypes::getOptions()),
                'mapped'   => false,
                'attr'     => [
                    'class' => 'yjmSelect2',
                    'help'  => 'The role represents the permission level to perform changes on the system. ROLE ADMIN is the higher permissive.',
                ],
                'required' => true,
            ]);

        $isEdit = $options['isEdit'];
        if (!$isEdit) {
            $builder->add('password', PasswordType::class, [
                'mapped' => false,
                'attr'   => [
                    'class'       => 'form-control',
                    'placeholder' => 'Password',
                    'minlength'   => 2,
                    'maxlength'   => 255,
                    'help'        => 'Initially this password will be used to authenticate the user. Later the user is able to change this password for a new one.',
                ],
            ]);
        }
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'isEdit'     => null,
        ]);
    }
}