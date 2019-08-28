<?php

namespace App\Form;

use App\Entity\Persons;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifyUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname')
            ->add('firstname')
            ->add('birthday')
            ->add('password')
            ->add('address')
            ->add('phoneNbr')
            ->add('email')
            ->add('country')
            ->add('zipcode')
            ->add('city')
            ->add('dateRegister')
            ->add('dateModify')
            ->add('company')
            ->add('adminSite')
            ->add('volunteer')
            ->add('internal')
            ->add('ClientPar')
            ->add('ClientPro')
            ->add('service')
            ->add('warehouse')
            ->add('signup')
            ->add('calendars')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Persons::class,
        ]);
    }
}
