<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchProfessionalsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du professionnel',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ex: Jean Dupont',
                    'class' => 'form-control'
                ]
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ex: Paris',
                    'class' => 'form-control'
                ]
            ])
            ->add('style', TextType::class, [
                'label' => 'Style/Spécialité',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ex: Coiffure, Maquillage',
                    'class' => 'form-control'
                ]
            ])
            ->add('Rechercher', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // No data_class because we're just searching, not saving to DB
        ]);
    }
}
