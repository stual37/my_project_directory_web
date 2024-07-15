<?php

namespace App\Factory;

use DateTimeImmutable;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;

class FormListenerFactory {

    public function __construct(private SluggerInterface $slugger)
    {
        # code...
    }

    /**
     * Undocumented function
     *
     * @param string $field
     * @return callable
     */
    public function autoSlug(string $field): callable
    {
        return function(PreSubmitEvent $event) use ($field) {
            $data = $event->getData();
            if(empty($data['slug'])) {
                //$slugger = new AsciiSlugger();
                $data['slug'] = strtolower($this->slugger->slug($data[$field]));
                $event->setData($data);
            }
        };
    }

    /**
     * Undocumented function
     *
     * @return callable
     */
    public function timeStamps(): callable
    {
        return function (SubmitEvent $event) {
            $data = $event->getData();
            $data->setUpdatedAt(new DateTimeImmutable());
            if(!($data->getId())) {
                $data->setCreatedAt(new DateTimeImmutable());
            }
            $event->setData($data);
        };
    }
}