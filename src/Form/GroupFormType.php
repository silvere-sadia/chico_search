<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\Unit;
use App\Enum\CriteriaValueType;
use App\Enum\SelectMode;
use App\Form\CriteriaType;
use App\Form\CriteriaWithRangeType;
use App\Form\EventSubscriber\AddRangeFieldSubscriber;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\LiveComponent\Form\Type\LiveCollectionType;
use Symfonycasts\DynamicForms\DependentField;
use Symfonycasts\DynamicForms\DynamicFormBuilder;

class GroupFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $entity = $options['data_class'];

        $builder = new DynamicFormBuilder($builder);
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'priority' => 10,
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type of Data',
                'choices'  => $entity::getAvailablesTypes(),
                // 'data' => 'text',
                'priority' => 9,
            ])
        ;

        if ($options['type'] === 'range') {
            $builder->add('rangeStart', NumberType::class, [
                'label' => 'Range Start',
                'required' => false,
                'priority' => 6,
            ])
            ->add('rangeEnd', NumberType::class, [
                'label' => 'Range End',
                'required' => false,
                'priority' => 5,
            ])
            ;
        }

        $builder->add('selectMode', ChoiceType::class, [
                'label' => 'Select Mode',
                'choices'  => $entity::getAvailablesSelectMode(),
            ])
            ->add('unit', EntityType::class, [
                'label' => 'Unit',
                'class' => Unit::class,
                'choice_label' => 'name',
                'required' => false,
            ])
            ->add('position', IntegerType::class, [
                'required' => false,
            ])
            ->add('color', ColorType::class, [
                'required' => false,
            ])
            ->add('childsLimit', IntegerType::class, [
                'required' => false,
            ])
            ->add('isActive', CheckboxType::class, [
                'required' => false,
            ])
        ;


        // $builder->addDependent('rangeStart', 'type', function(DependentField $field, string $type) use ($options) {
        //         if ($type == 'range') {
        //             $field->add(NumberType::class, [
        //                 'label' => 'Range Start',
        //                 'required' => false,
        //                 'data' => $options['start'],
        //                 'priority' => 8,
        //             ]);
        //         }
        //     }
        // ); 

        // $builder->addDependent('rangeEnd', 'type', function(DependentField $field, string $type)  use ($options) {
        //         if ($type == 'range') {
        //             $field->add(NumberType::class, [
        //                 'label' => 'Range End',
        //                 'required' => false,
        //                 'data' => $options['end'],
        //                 'priority' => 7,
        //             ]);
        //         }
        //     }
        // ); 
        
        
        if ($options['route_name'] !== 'app_admin_group_new') {
            if ($options['type'] === 'range') {
                $builder->add('criterias', LiveCollectionType::class, [
                    'entry_type' => CriteriaWithRangeType::class,
                    'priority' => 6
                ])
                ;
            } else {
                $builder->add('criterias', LiveCollectionType::class, [
                    'entry_type' => CriteriaType::class,
                    'priority' => 6
                ])
                ;
            }
        }
            
        // $builder->addEventSubscriber(new AddRangeFieldSubscriber());

        $builder->add('submit', SubmitType::class, [
            'label' => 'Save',
            'priority' => -1,
            'attr' => [
                'class' => 'btn btn-primary',
            ],
        ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Group::class,
            'route_name' => null,
            'type' => 'text',
            'start' => null,
            'end' => null,
        ]);
    }
}
