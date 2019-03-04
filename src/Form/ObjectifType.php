<?php

namespace App\Form;

use App\Entity\Objectif;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class ObjectifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('montant',MoneyType::class)
            ->add('veterinaire',EntityType::class,[
                'class' => 'App\Entity\Veterinaire',
                'placeholder' => 'Sélectionnez le vétérinaire',
                'choice_label' => 'getNomVille'
            ])
            ->add('produit',EntityType::class,[
                'class' => 'App\Entity\Produit',
                'placeholder' => 'Sélectionnez un produit',
                'choice_label' => 'getNomProduit'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Objectif::class,
        ]);
    }
}
