<?php

namespace App\Form;

use App\Entity\Criteria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfonycasts\DynamicForms\DynamicFormBuilder;

class CriteriaWithRangeType extends AbstractType
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
            ->add('rangeStart', NumberType::class, [
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Criteria::class,
        ]);
    }
}
