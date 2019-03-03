<?php

namespace App\Form;

use App\Entity\Veterinaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('urlImage',UrlType::class,['label' => 'URL Photo','required'=> true])
            ->add('altImage',TextType::class,['label' => 'ALT Photo','required'=> true])
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
