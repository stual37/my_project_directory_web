<?php

namespace App\Form;

use App\DTO\ContactDTO;
use App\DTO\ContactRegistration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'empty_data' => ' ',
                'label' => 'Username',
                'attr' => [
                    'aria-label' => 'Nom d\'utilisateur'
                ]
            ])
            ->add('email', EmailType::class, [
                'empty_data' => ' ',
                'label' => 'Email',
                'attr' => [
                    'aria-label' => 'Email'
                ]
            ])
            ->add('subject', TextType::class, [
                'label' => 'Sujet',
                'disabled' => 'true',
                'attr' => [
                    'aria-label' => 'Sujet'
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'disabled' => 'true',
                'attr' => [
                    'aria-label' => 'message'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'aria-label' => 'Bouton pour envoyer',
                    'attr' => [
                        'aria-label' => 'Bouton pour envoyer'
                    ]
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactRegistration::class,
        ]);
    }
}
