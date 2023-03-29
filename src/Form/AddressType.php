<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom daddresse',
                'attr' => [
                    'placeholder' => 'Nommez votre addresse'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prenom daddresse',
                'attr' => [
                    'placeholder' => 'Nommez votre prenom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom ',
                'attr' => [
                    'placeholder' => 'Nommez votre nom'
                ]
            ])
            ->add('company', TextType::class, [
                'label' => 'Nom societe',
                'attr' => [
                    'placeholder' => 'facultatif'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Votre addresse',
                'attr' => [
                    'placeholder' => 'Lot A 241 A Bis B'
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'Votre Code Postal',
                'attr' => [
                    'placeholder' => 'Nommez votre code postal'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Votre villee',
                'attr' => [
                    'placeholder' => 'Nommez votre ville'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Votre pays',
                'attr' => [
                    'placeholder' => 'votre pays'
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Votre telephone',
                'attr' => [
                    'placeholder' => 'votre telephone'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider mon addresse'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
