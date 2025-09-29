<?php
// src/Form/ProductSearchType.php

namespace App\Form;

use App\DTO\ProductSearchCriteria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProductSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('searchTerm', TextType::class, [
                'label' => 'Recherche',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Nom ou description...',
                ],
            ])
            ->add('minPrice', NumberType::class, [
                'label' => 'Prix min',
                'required' => false,
                'scale' => 2,
                'attr' => [
                    'step' => '0.01',
                    'min' => '0',
                ],
            ])
            ->add('maxPrice', NumberType::class, [
                'label' => 'Prix max',
                'required' => false,
                'scale' => 2,
                'attr' => [
                    'step' => '0.01',
                    'min' => '0',
                ],
            ])
            ->add('minQuantity', NumberType::class, [
                'label' => 'Quantité min',
                'required' => false,
                'attr' => [
                    'min' => '0',
                ],
            ])
            ->add('maxQuantity', NumberType::class, [
                'label' => 'Quantité max',
                'required' => false,
                'attr' => [
                    'min' => '0',
                ],
            ])
            ->add('minWeight', NumberType::class, [
                'label' => 'Poids min',
                'required' => false,
                'scale' => 2,
                'attr' => [
                    'step' => '0.01',
                    'min' => '0',
                ],
            ])
            ->add('maxWeight', NumberType::class, [
                'label' => 'Poids max',
                'required' => false,
                'scale' => 2,
                'attr' => [
                    'step' => '0.01',
                    'min' => '0',
                ],
            ])
            ->add('minRating', NumberType::class, [
                'label' => 'Note min',
                'required' => false,
                'scale' => 1,
                'attr' => [
                    'step' => '0.1',
                    'min' => '0',
                    'max' => '5',
                ],
            ])
            ->add('maxRating', NumberType::class, [
                'label' => 'Note max',
                'required' => false,
                'scale' => 1,
                'attr' => [
                    'step' => '0.1',
                    'min' => '0',
                    'max' => '5',
                ],
            ])
            ->add('categories', ChoiceType::class, [
                'label' => 'Catégories',
                'required' => false,
                'multiple' => true,
                'expanded' => false,
                'choices' => $options['categories'] ?? [],
                'attr' => [
                    'class' => 'select2',
                ],
            ])
            ->add('tags', ChoiceType::class, [
                'label' => 'Tags',
                'required' => false,
                'multiple' => true,
                'expanded' => false,
                'choices' => $options['tags'] ?? [],
                'attr' => [
                    'class' => 'select2',
                ],
            ])
            ->add('isActive', ChoiceType::class, [
                'label' => 'Statut',
                'required' => false,
                'choices' => [
                    'Actif' => true,
                    'Inactif' => false,
                ],
                'placeholder' => 'Tous',
            ])
            ->add('createdAfter', DateType::class, [
                'label' => 'Créé après',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('createdBefore', DateType::class, [
                'label' => 'Créé avant',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('sortBy', ChoiceType::class, [
                'label' => 'Trier par',
                'choices' => [
                    'Nom' => 'name',
                    'Prix' => 'price',
                    'Quantité' => 'quantity',
                    'Note' => 'rating',
                    'Date de création' => 'createdAt',
                ],
            ])
            ->add('sortOrder', ChoiceType::class, [
                'label' => 'Ordre',
                'choices' => [
                    'Croissant' => 'ASC',
                    'Décroissant' => 'DESC',
                ],
            ])
            ->add('search', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => ['class' => 'btn btn-primary'],
            ])
            ->add('reset', SubmitType::class, [
                'label' => 'Réinitialiser',
                'attr' => ['class' => 'btn btn-secondary'],
                'validation_groups' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductSearchCriteria::class,
            'method' => 'GET',
            'csrf_protection' => false,
            'categories' => [],
            'tags' => [],
        ]);

        $resolver->setAllowedTypes('categories', 'array');
        $resolver->setAllowedTypes('tags', 'array');
    }

    public function getBlockPrefix(): string
    {
        return ''; // Retire le préfixe pour avoir des URLs propres
    }
}