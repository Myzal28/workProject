<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SuiviType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomContact',TextType::class,['required' => true])
            ->add('commentaire',TextareaType::class,['required' => true])
            ->add('dateAppel',DateType::class,[
                'required' => true,
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd'
            ])
            ->add('veterinaire',EntityType::class,[
                'class' => 'App\Entity\Veterinaire',
                'placeholder' => 'Sélectionnez le vétérinaire',
                'choice_label' => 'getNomVille'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
