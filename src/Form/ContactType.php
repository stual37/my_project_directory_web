<?php

namespace App\Form;

use App\DTO\ContactDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'empty_data' => ' ',
                'label' => 'Nom',
                'attr' => [
                    'aria-label' => 'Email'
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
                'empty_data' => ' ',
                'label' => 'Sujet',
                'attr' => [
                    'aria-label' => 'Sujet'
                ]
            ])
            ->add('message', TextareaType::class, [
                'empty_data' => ' ',
                'label' => 'Message',
                'attr' => [
                    'minlength' => 8,
                    'maxlength' => 500,
                    'aria-label' => 'Message'
                ],
            ])
            ->add('service', ChoiceType::class, [
                'choices' => [
                    'comptabilité' => 'compta@demo.fr',
                    'Support' => 'support@demo.fr',
                    'Service communication' => 'com@demo.fr',
                    'Sevrice commerce' => 'commerce@demo.fr',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'aria-label' => 'Bouton pour envoyer'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactDTO::class,
        ]);
    }
}
