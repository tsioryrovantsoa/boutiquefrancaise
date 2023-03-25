<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => true,
                'label' => 'Mon adresse mail'
            ])
            ->add('firstname', TextType::class, [
                'disabled' => true,
                'label' => 'Mon prenom'
            ])
            ->add('lastname', TextType::class, [
                'disabled' => true,
                'label' => 'Mon nom'
            ])
            ->add(
                'old_password',
                PasswordType::class,
                [
                    'mapped' => false,
                    'label' => 'Mon mot de passe actuel',
                    'attr' => [
                        'placeholder' => 'Veuillez saisir votre mot de passe actuel'
                    ]
                ]
            )
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
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
                'label' => "Mettre a jour"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
