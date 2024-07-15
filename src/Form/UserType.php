<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'empty_data' => ' ',
                'label' => 'nom d\'utilisateur',
                'attr' => [
                    'aria-label' => 'nom d\'utilisateur',
                    'aria-description' => 'nom que l\'utilisateur utilisera pour se connecter'
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'admin' => 'ROLE_ADMIN',
                    'utilisateur' => 'ROLE_ADMIN'
                ],
                'multiple' => true
            ])
            ->add('password', PasswordType::class, [
                'empty_data' => ' ',
                'label' => 'mot de passe de l\'utilisateur',
                'attr' => [
                    'aria-label' => 'mot de passe de l\'utilisateur',
                    'aria-description' => 'mot de passe de l\'utilisateur',
                    'id' => 'password'
                ]
            ])
            ->add('email', EmailType::class, [
                'empty_data' => ' ',
                'required' => true,
                'label' => 'email de l\'utilisateur',
                'attr' => [
                    'aria-label' => 'email de l\'utilisateur',
                    'aria-description' => 'email de l\'utilisateur'
                ]
            ])
            ->add('isVerified', CheckboxType::class, [
                'empty_data' => ' ',
                'required' => false,
                'label' => 'Validé',
                'attr' => [
                    'aria-label' => 'validation de l\'utilisateur',
                    'aria-description' => 'permet de valider l\'utilisateur'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'aria-label' => 'Sauvegarder l\'utilisateur',
                    'aria-description' => 'Bouton pour sauvegarder l\'utilisateur'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
