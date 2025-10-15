<?php

namespace App\Twig\Components;

use App\Entity\Group;
use App\Form\GroupFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent()]
class GroupForm extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $route = '';

    /**
     * The initial data used to create the form.
     */
    #[LiveProp]
    public ?Group $initialFormData = null;

    protected function instantiateForm() : FormInterface {
        return $this->createForm(GroupFormType::class, $this->initialFormData , [
            'route_name'=>$this->route, 
            'type' => $this->initialFormData === null ? 'text' : $this->initialFormData->getType(),
            'start' => $this->initialFormData->getRangeStart(),
            'end' => $this->initialFormData->getRangeEnd(),
        ])
        ;
    }

    #[LiveAction]
    public function addCriteria()
    {
        // "formValues" represents the current data in the form
        // this modifies the form to add an extra comment
        // the result: another embedded comment form!
        // change "comments" to the name of the field that uses CollectionType
        $this->formValues['criterias'][] = [];
    }

    #[LiveAction]
    public function removeCriteria(#[LiveArg] int $index)
    {
        unset($this->formValues['criterias'][$index]);
    }

    #[LiveAction]
    public function refresh(EntityManagerInterface $entityManager)
    {
        $this->submitForm();

        $group = $this->getForm()->getData();
        $entityManager->persist($group);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_group_edit', ['id'=>$group->getId()], Response::HTTP_SEE_OTHER);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager)
    {
        $this->submitForm();

        $group = $this->getForm()->getData();
        $entityManager->persist($group);
        $entityManager->flush();

        // $route = $request->get('_route') == 'app_admin_group_new' ? 'app_admin_group_edit' : 'app_admin_group_show';
        
        if ($this->route == 'app_admin_group_new') {

            return $this->redirectToRoute('app_admin_group_edit', ['id'=>$group->getId()], Response::HTTP_SEE_OTHER);
        }
        
        return $this->redirectToRoute('app_admin_group_show', ['id'=>$group->getId()], Response::HTTP_SEE_OTHER);

    }
}
