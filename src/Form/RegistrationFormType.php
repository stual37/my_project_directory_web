<?php

namespace App\Form;

use App\Entity\User;
use App\Form\Type\CaptchaType;
use App\Form\Type\RepeatedPasswordType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    /**
     * Méthode qui permet de créer un formulaire
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'empty_data' => '',
                'label' => 'Nom d\'utilisateur',
                'attr' => [
                    'autocomplete' => 'username',
                    'min' => 5,
                    'max' => 16,
                    'aria-label' => 'nom d\'utilisateur'
                ],
                'row_attr' => [ 'id' => 'username'],
            ])
            ->add('email', RepeatedType::class, [
                'empty_data' => '',
                'type' => EmailType::class,
                'required' => true,
                'first_options'  => ['label' => 'Email'],
                'second_options' => ['label' => 'Répéter Email'],
                'invalid_message' => 'L\'email doit correspondre',
                'attr' => [
                    'autocomplete' => 'email',
                    'aria-label' => 'email'
                ],
                'row_attr' => [ 'id' => 'email'],
            ])
            ->add('password', RepeatedPasswordType::class, [
                'label' => ''
            ])
            ->add('agreedTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'J\'accepte le termes d\'utilisations',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous êtesd\'accord avec les termes d\'utilisations.',
                    ]),
                ],
                'row_attr' => [ 'id' => 'agreed-terms'],
            ])
            ->add('captcha', CaptchaType::class, [
                'mapped' => false,
                'route' => 'captcha',
                'row_attr' => [ 'id' => 'captcha'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'S\'enregistrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'route' => 'captcha'
        ]);
    }
}
