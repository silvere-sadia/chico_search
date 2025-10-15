<?php

// src/Form/EventSubscriber/AddNameFieldSubscriber.php
namespace App\Form\EventSubscriber;

use App\Form\CriteriaType;
use App\Form\CriteriaWithRangeType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\UX\LiveComponent\Form\Type\LiveCollectionType;

class AddRangeFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return [FormEvents::PRE_SET_DATA => 'preSetData'];
    }

    public function preSetData(FormEvent $event): void
    {
        $form = $event->getForm();

        if ($form->get('type')->getData() === 'range') {
            $form->add('criterias', LiveCollectionType::class, [
                'entry_type' => CriteriaWithRangeType::class,
                'priority' => 6
                ])
                ;
        }else {
            $form->add('criterias', LiveCollectionType::class, [
                'entry_type' => CriteriaType::class,
                'priority' => 6
            ])
            ;
        }
    }
}