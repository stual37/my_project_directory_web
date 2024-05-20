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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RecipeType extends AbstractType
{

    public function __construct(private FormListenerFactory $formListenerFactory)
    {
        # code...
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'empty_data' => ''
            ])
            ->add('slug', TextType::class, [
                'required' => false,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Catégorie',
                'expanded' => true,
            ])
            ->add('content', TextareaType::class, [
                'empty_data' => ''
            ])
            ->add('duration')
            ->add('thumbnailFile', FileType::class)
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer'
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
