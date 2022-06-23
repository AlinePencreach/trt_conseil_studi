<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('name', TextType::class, ['label' => 'Nom/Prénom'])
            ->add('metier', TextType::class)
            ->add('CV', FileType::class, [
                'label' => 'CV',
                'mapped' => false,
                

            // // make it optional so you don't have to re-upload the PDF file
            // // every time you edit the Product details
                

            // // unmapped fields can't define their validation using annotations
            // // in the associated entity, so you can use the PHP constraint classes
              
            ])
            ->add('submit', SubmitType::class, ['label' => 'Modifier']
            );

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
