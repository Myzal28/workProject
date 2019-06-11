<?php

namespace App\Form;

use App\Entity\Persons;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class SignupPersonType extends AbstractType
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
            ->add('password', PasswordType::class)
            ->add('confirm_password', PasswordType::class)
            ->add('phoneNbr')
            ->add('email')
            ->add('country')
            ->add('zipcode')
            ->add('address')
            ->add('company')
            ->add('type_choice', ChoiceType::class, [
                'choices'  => [
                    'Intern' => 1,
                    'Volunteer' => 2,
                    'Client Pro'=> 3,
                    'Client Par' => 4
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Persons::class,
        ]);
    }
}
