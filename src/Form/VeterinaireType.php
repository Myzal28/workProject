<?php

namespace App\Form;

use App\Entity\Veterinaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class VeterinaireType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,['required'=>true])
            ->add('adresse',TextType::class,['required'=> true])
            ->add('codePostal',TextType::class,['required'=> true])
            ->add('ville',TextType::class,['required'=> true])
            ->add('telephone',TextType::class,['required'=> true])
            ->add('photo',PhotoType::class,[
                'data_class' => 'App\Entity\Photo',
                'required'=> true
            ])
            ->add('activites',EntityType::class,[
                'class' => 'App\Entity\Activite',
                'multiple' => true,
                'choice_label' => 'libelle'
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Veterinaire::class,
        ]);
    }
}
