<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\Unit;
use App\Form\CriteriaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $entity = $options['data_class'];
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type of Data',
                'choices'  => $entity::getAvailablesTypes(),
            ])
            ->add('selectMode', ChoiceType::class, [
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

        if ($options['route_name'] !== 'app_admin_group_new') {
            $builder->add('criterias', CollectionType::class, [
                'label' => false,
                'entry_type' => CriteriaType::class, // Assuming a TagType form
                'entry_options' => [
                    'attr' => ['class' => 'form-group'],
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false, // Important for managing relationships
            ])
        ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Group::class,
            'route_name' => null,
        ]);
    }
}
