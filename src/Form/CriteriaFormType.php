<?php

namespace App\Form;

use App\Entity\Criteria;
use App\Entity\Group;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfonycasts\DynamicForms\DependentField;
use Symfonycasts\DynamicForms\DynamicFormBuilder;

class CriteriaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder = new DynamicFormBuilder($builder);

        $builder->add('name', TextType::class, [
                'priority' => 10,
            ])
            ->add('color', ColorType::class, [
                'required' => false,
                'priority' => 9,
            ])
            ->add('position', IntegerType::class, [
                'required' => false,
                'priority' => 8,
            ])
            ->add('isActive', CheckboxType::class, [
                'required' => false,
                'priority' => 7,
            ])
            ->add('parent', HiddenType::class)
        ;

        //  $builder->addDependent('rangeStart', 'parent', function(DependentField $field, string $parent){
        //         if ($parent == 'range') {
        //             $field->add(NumberType::class, [
        //                 'label' => 'Range Start',
        //                 'priority' => 6,
        //             ]);
        //         }
        //     }
        // ); 

        // $builder->addDependent('rangeEnd', 'typeValue', function(DependentField $field, string $parent){
        //         if ($parent == 'range') {
        //             $field->add(NumberType::class, [
        //                 'label' => 'Range End',
        //                 'priority' => 5,
        //             ]);
        //         }
        //     }
        // ); 
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Criteria::class,
            'is_range' => false,
        ]);
    }
}
