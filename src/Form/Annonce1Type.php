<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class Annonce1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre du poste'])
            ->add('description', TextType::class, ['label' => 'Description du poste'])
            ->add('etablissement', TextType::class, ['label' => 'Etablissement'])
            ->add('adress', TextType::class, ['label' => 'Adresse'])
            ->add('salaire', TextType::class, ['label' => 'Salaire'])
            ->add('Ajouter', SubmitType::class);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
       
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        
        ]);
    }
}
