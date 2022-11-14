<?php

namespace App\Form;

use App\Entity\Business;
use App\Entity\IndustrySector;
use App\Repository\IndustrySectorRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
                    'maxlength'   => 255
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
                ],
                'choice_label'  => 'name',
            ])
            ->add('address', TextType::class, [
                'attr' => [
                    'placeholder' => 'Address',
                    'class'       => 'form-control form-control-border',
                    'minlength'   => 2,
                    'maxlength'   => 255
                ],

            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'placeholder' => 'City',
                    'class'       => 'form-control form-control-border',
                    'minlength'   => 2,
                    'maxlength'   => 255
                ],
            ])
            ->add('state', TextType::class, [
                'attr' => [
                    'placeholder' => 'State',
                    'class'       => 'form-control form-control-border',
                    'minlength'   => 2,
                    'maxlength'   => 255
                ],
            ])
            ->add('zipCode', TextType::class, [
                'attr' => [
                    'placeholder' => 'Zip Code',
                    'class'       => 'form-control form-control-border zip-code',
                    'minlength'   => 2,
                    'maxlength'   => 15,
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
                ],
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class'     => 'form-control form-control-border',
                    'placeholder' => 'Business email',
                    'minlength' => 2,
                    'maxlength' => 255
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