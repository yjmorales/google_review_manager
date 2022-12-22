<?php

namespace App\Form;

use App\Entity\Business;
use App\Entity\IndustrySector;
use App\Repository\IndustrySectorRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BusinessFormType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Business name',
                    'class'       => 'form-control form-control-border',
                    'minlength'   => 2,
                    'maxlength'   => 255,
                    'help'        => 'This value represents the business name.',
                ],
            ])
            ->add('industrySector', EntityType::class, [
                'required'      => false,
                'label'         => 'Industry Sector',
                'class'         => IndustrySector::class,
                'query_builder' => function (IndustrySectorRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.name', 'ASC');
                },
                'attr'          => [
                    'class' => 'yjmSelect2',
                    'help'  => 'This value represents what the business does.',

                ],
                'choice_label'  => 'name',
            ])
            ->add('place', HiddenType::class, [
                'required' => false,
                'mapped'   => false,
                'attr'     => [
                    'data-id' => 'business_form_place',
                ]
            ])
            ->add('address', TextType::class, [
                'attr' => [
                    'placeholder' => 'Address',
                    'class'       => 'form-control form-control-border',
                    'data-id'     => 'business_form_address',
                    'minlength'   => 2,
                    'maxlength'   => 255,
                    'help'        => 'Address of the business location.',
                ],
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'placeholder' => 'City',
                    'class'       => 'form-control form-control-border',
                    'minlength'   => 2,
                    'maxlength'   => 255,
                    'help'        => 'City where the business is located.',
                    'data-id'     => 'business_form_city',
                ],
            ])
            ->add('state', TextType::class, [
                'attr' => [
                    'placeholder' => 'State',
                    'class'       => 'form-control form-control-border',
                    'minlength'   => 2,
                    'maxlength'   => 255,
                    'help'        => 'State where the business is located.',
                    'data-id'     => 'business_form_state',
                ],
            ])
            ->add('zipCode', TextType::class, [
                'attr' => [
                    'placeholder' => 'Zip Code',
                    'class'       => 'form-control form-control-border zip-code',
                    'minlength'   => 2,
                    'maxlength'   => 15,
                    'help'        => 'Zip Code where the business is located.',
                    'data-id'     => 'business_form_zipCode',
                ],
            ])
            ->add('active', CheckboxType::class, [
                'required' => false,
                'label'    => false,
                'attr'     => [
                    'data-switch'    => 'true',
                    'data-on-text'   => 'Active',
                    'data-off-text'  => 'Inactive',
                    'data-off-color' => 'danger',
                    'help'           => 'An Indicator of whether the business is currently open and generating goods and/or services.'
                ],
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class'       => 'form-control form-control-border',
                    'placeholder' => 'Business email',
                    'minlength'   => 2,
                    'maxlength'   => 255,
                    'help'        => 'This value represents the official business email.',
                ],
            ]);
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Business::class,
        ]);
    }
}