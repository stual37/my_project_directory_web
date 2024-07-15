<?php

namespace App\Form;

use DateTimeImmutable;
use App\Entity\Category;
use App\Entity\Recipe;
use App\Factory\FormListenerFactory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CategoryType extends AbstractType
{

    public function __construct(private FormListenerFactory $formListenerFactory)
    {
        # code...
    }

    /**
     * Méthode qui permet de créer le formulaire
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'empty_data' => ''
            ])
            ->add('slug', TextType::class, [
                'empty_data' => '',
                'required' => false,
            ])
            ->add('recipes', EntityType::class, [
                'class' => Recipe::class,
                'choice_label' => 'title',
                'label' => 'Recettes',
                'multiple' => true,
                'by_reference' => false,
                'expanded' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer'
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->formListenerFactory->autoSlug('name'))
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
            $data['slug'] = strtolower($slugger->slug($data['name']));
            $event->setData($data);
        }
    }
*/
    public function setDate(SubmitEvent $event): void
    {
        $data = $event->getData();
        if(!($data instanceof Category)) {
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
            'data_class' => Category::class,
        ]);
    }

    private function getSession(): SessionInterface
    {

    }
}
