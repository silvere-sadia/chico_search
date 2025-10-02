<?php
// src/Form/CriteriaGroupType.php

namespace App\Form;

use App\Entity\CriteriaGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CriteriaGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du groupe',
                'attr' => ['maxlength' => 100]
            ])
            ->add('code', TextType::class, [
                'label' => 'Code unique',
                'help' => 'Code utilisé pour identifier le groupe dans le système',
                'attr' => ['maxlength' => 50]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['rows' => 3]
            ])
            ->add('displayMode', ChoiceType::class, [
                'label' => 'Mode d\'affichage',
                'choices' => CriteriaGroup::getAvailableDisplayModes(),
                'placeholder' => 'Choisir un mode d\'affichage',
            ])
            ->add('isMultiple', CheckboxType::class, [
                'label' => 'Sélection multiple',
                'required' => false,
                'help' => 'Permet de sélectionner plusieurs valeurs (pour les modes choice)',
            ])
            ->add('maxSelections', IntegerType::class, [
                'label' => 'Nombre maximum de sélections',
                'required' => false,
                'help' => 'Limite le nombre de critères pouvant être sélectionnés (0 = illimité)',
                'attr' => ['min' => 0]
            ])
            ->add('sortOrder', IntegerType::class, [
                'label' => 'Ordre d\'affichage',
                'required' => false,
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Actif',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CriteriaGroup::class,
        ]);
    }
}