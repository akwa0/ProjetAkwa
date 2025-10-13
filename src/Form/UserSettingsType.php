<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Utilisateur;

class UserSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class)
            ->add('email', EmailType::class)
            ->add('description', TextareaType::class)
            ->add('profilImage', FileType::class,[
                'mapped'=>false,
                'required'=>false,
            ])
            ->add('telephone', NumberType::class)
            ->add('adresse', TextType::class)
            ->add('codePostal', TextType::class)
            ->add('ville', TextType::class)
            ->add('pays', TextType::class)
            ->add('style', TextType::class)
            ->add('experience', NumberType::class);
            



        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
            // Configure your form options here
        ]);
    }
}
