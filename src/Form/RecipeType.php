<?php

namespace App\Form;

use App\Entity\Recipe;
use DateTimeImmutable;

use App\Entity\Category;
use App\Factory\FormListenerFactory;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\SubmitEvent;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RecipeType extends AbstractType
{

    public function __construct(private FormListenerFactory $formListenerFactory)
    {
        # code...
    }

    /**
     * Méthode qui permet de créer le formulaire des recettes
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'empty_data' => '',
                'attr' => [
                    'aria-label' => 'Titre de la recette',
                    'aria-description' => 'Titre de la recette, il doit être unique'
                ]
            ])
            ->add('slug', HiddenType::class, [
                'required' => false,
                'attr' => [
                    'aria-label' => 'SLug de la recette',
                    'aria-description' => 'Slug de la recette avec une valeur unique'
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Catégorie',
                'expanded' => true,
                'attr' => [
                    'aria-label' => 'Catégorie de la recette',
                    'aria-description' => 'Catégorie de la recette'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Durée',
                'empty_data' => '',
                'attr' => [
                    'aria-label' => 'Contenu de la recette',
                    'aria-description' => 'Description détaillé de la recette avec les étapes pas à pas'
                ]
            ])
            ->add('duration', IntegerType::class, [
                'empty_data' => '',
                'attr' => [
                    'aria-label' => 'Durée de la recette',
                    'aria-description' => 'Durée totale pour executer la recette en minutes'
                ]
            ])
            ->add('thumbnailFile', FileType::class, [
                'attr' => [
                    'aria-label' => 'Fichier image',
                    'aria-description' => 'Image de la recette'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'aria-label' => 'Sauvegarder la recette',
                    'aria-description' => 'Bouton pour sauvegarder la recette'
                ]
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->formListenerFactory->autoSlug('title'))
            //->addEventListener(FormEvents::SUBMIT, $this->setDate(...))
            ->addEventListener(FormEvents::SUBMIT, $this->formListenerFactory->timeStamps())
        ;
    }
/*
    public function autoSlug(PreSubmitEvent $event): void
    {
        $data = $event->getData();
        if(empty($data['slug'])) {
            $slugger = new AsciiSlugger();
            $data['slug'] = strtolower($slugger->slug($data['title']));
            $event->setData($data);
        }
    }
*/
    public function setDate(SubmitEvent $event): void
    {
        $data = $event->getData();
        if(!($data instanceof Recipe)) {
            return;
        }
        $data->setUpdatedAt(new DateTimeImmutable());
        if(!($data->getId())) {
            $data->setCreatedAt(new DateTimeImmutable());
        }
        $event->setData($data);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
