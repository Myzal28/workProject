<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifyUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname')
            ->add('firstname')
            ->add('birthday', DateType::class, [
                'format' => 'yyyy-MM-dd',
                'widget' => 'single_text',
            ])
            ->add('phoneNbr')
            ->add('country')
            ->add('city')
            ->add('address')
            ->add('zipCode')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }
}
