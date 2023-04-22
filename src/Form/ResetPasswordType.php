<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe ne sont pas identiques',
                'label' => 'Mon nouveau mot de passe',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Mon nouveau nouveau mot de passe'
                ],
                'first_options' => ['label' => 'Votre nouveau mot de passe', 'attr' => [
                    'placeholder' => 'Votre mot de passe'
                ]],
                'second_options' => ['label' => 'Confirmer nouveau votre mot de passe', 'attr' => [
                    'placeholder' => 'Confirmer votre mot de passe'
                ]]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Mettre a jour mon mot de passe",
                'attr' => [
                    'class' => 'btn-block btn-info'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
