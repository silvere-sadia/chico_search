<?php
// src/Form/SearchCriteriaType.php

namespace App\Form;

use App\Entity\SearchCriteria;
use App\Entity\CriteriaGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class SearchCriteriaType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $entity = $options['data_class'];
        $types = $entity::getAvailableTypes();
        $operators = $entity::getAvailableOperators();
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du critère',
                'attr' => ['maxlength' => 100]
            ])
            ->add('fieldName', TextType::class, [
                'label' => 'Nom du champ',
                'help' => 'Nom technique utilisé dans les requêtes',
                'attr' => ['maxlength' => 100]
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type de données',
                'choices' => $types,
                'placeholder' => 'Choisir un type',
            ])
            ->add('criteriaGroup', EntityType::class, [
                'label' => 'Groupe',
                'class' => CriteriaGroup::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisir un groupe',
            ])
            ->add('operators', ChoiceType::class, [
                'label' => 'Opérateurs disponibles',
                'choices' => $operators,
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'attr' => ['class' => 'select2'],
            ])
            // ->add('options', TextareaType::class, [
            //     'label' => 'Options (JSON)',
            //     'required' => false,
            //     'help' => 'Options spécifiques au format JSON. Pour les types choice, utiliser {"choices": {"Option1": "value1", "Option2": "value2"}}',
            //     'attr' => ['rows' => 4]
            // ])
            ->add('sortOrder', IntegerType::class, [
                'label' => 'Ordre d\'affichage',
                'required' => false,
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Actif',
                'required' => false,
            ])
            ->add('isRequired', CheckboxType::class, [
                'label' => 'Requis',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchCriteria::class,
            'product_filters' => null,
            'date_filters' => null,
        ]);
    }
}