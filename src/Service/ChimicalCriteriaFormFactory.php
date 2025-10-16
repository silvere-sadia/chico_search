<?php 

namespace App\Service;

use App\Entity\Group;
use App\Form\ChimicalCriteriaType;
use App\Repository\GroupRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class ChimicalCriteriaFormFactory
{
    public function __construct(
        private GroupRepository $groupRepository,
        private FormFactoryInterface $formFactory
    ) {}


    public function makeForm(array $groups = []): FormInterface
    {
        $activeGroups = empty($groups) ? $this->groupRepository->findBy(['isActive'=>true]) : $groups;

        $formBuilder = $this->formFactory->createBuilder(ChimicalCriteriaType::class);

        foreach ($activeGroups as $group) {
            $this->addGroupToForm($formBuilder, $group);
        }
        
        // Ajouter les boutons d'action
        $formBuilder
            ->add('submit', SubmitType::class, [
            'label' => 'Save',
            'attr' => ['class' => 'btn btn-primary']
            ])->add('reset', SubmitType::class, [
                'label' => 'Réinitialiser',
                'attr' => ['class' => 'btn btn-secondary']
            ])
        ;
            
        $form = $formBuilder->getForm();
        // dd($formTypeClass, $activeGroups, $formBuilder, $form);
        return $form;
    }

    /**
     * Ajoute un groupe de critères au formulaire
     */
    private function addGroupToForm(FormBuilderInterface $formBuilder, Group $group): void
    {
        $formBuilder->add($group->getId(), ChoiceType::class, $this->makeOptionsOfForm($group));
    }

    /**
     * Mettre en le type du formulaire du groupe
     */
    private function makeArrayOfChoice(Group $group): array
    {
        $choices = [];

        foreach ($group->getCriterias() as $criteria) {
            $choices[$criteria->getName()] = $criteria->getId();
        }

        return $choices;
    }

    /**
     * Mettre en forme les choix du groupe
     */
    private function makeOptionsOfForm(Group $group): array
    {
        $options = [
            'label' => $group->getName(),
            'required' => false,
            'mapped' => false,
            'expanded' => true,
        ];

        if ($group->getSelectMode() == $group::SELECT_MODE_MULTIPLE) {
            $options['multiple'] = true;
        }

        $options['choices'] = $this->makeArrayOfChoice($group);

        return $options;
    }
}
