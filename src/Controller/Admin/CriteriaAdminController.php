<?php
// src/Controller/Admin/CriteriaAdminController.php

namespace App\Controller\Admin;

use App\Entity\CriteriaGroup;
use App\Entity\SearchCriteria;
use App\Form\CriteriaGroupType;
use App\Form\SearchCriteriaType;
use App\Repository\CriteriaGroupRepository;
use App\Repository\SearchCriteriaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/criteria')]
class CriteriaAdminController extends AbstractController
{
    #[Route('/', name: 'admin_criteria_index')]
    public function index(
        CriteriaGroupRepository $criteriaGroupRepository
    ): Response {
        $groups = $criteriaGroupRepository->findAll();

        return $this->render('admin/criteria/index.html.twig', [
            'groups' => $groups,
        ]);
    }

    #[Route('/group/new', name: 'admin_criteria_group_new')]
    public function newGroup(Request $request, EntityManagerInterface $entityManager): Response
    {
        $group = new CriteriaGroup();
        $form = $this->createForm(CriteriaGroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($group);
            $entityManager->flush();

            $this->addFlash('success', 'Groupe de critères créé avec succès.');

            return $this->redirectToRoute('admin_criteria_index');
        }

        return $this->render('admin/criteria/new_group.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // #[Route('/criteria/new', name: 'admin_criteria_new')]
    // public function newCriteria(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $criteria = new SearchCriteria();
    //     $form = $this->createForm(SearchCriteriaType::class, $criteria);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($criteria);
    //         $entityManager->flush();

    //         $this->addFlash('success', 'Critère de recherche créé avec succès.');

    //         return $this->redirectToRoute('admin_criteria_index');
    //     }

    //     return $this->render('admin/criteria/new_criteria.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    #[Route('/group/{id}/edit', name: 'admin_criteria_group_edit')]
    public function editGroup(Request $request, CriteriaGroup $group, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CriteriaGroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Groupe de critères modifié avec succès.');

            return $this->redirectToRoute('admin_criteria_index');
        }

        return $this->render('admin/criteria/edit_group.html.twig', [
            'form' => $form->createView(),
            'group' => $group,
        ]);
    }

    #[Route('/criteria/{id}/edit', name: 'admin_criteria_edit')]
    public function editCriteria(Request $request, SearchCriteria $criteria, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SearchCriteriaType::class, $criteria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Critère de recherche modifié avec succès.');

            return $this->redirectToRoute('admin_criteria_index');
        }

        return $this->render('admin/criteria/edit_criteria.html.twig', [
            'form' => $form->createView(),
            'criteria' => $criteria,
        ]);
    }

    #[Route('/criteria/new', name: 'admin_criteria_new')]
    public function newCriteria(Request $request, EntityManagerInterface $entityManager, CriteriaGroupRepository $groupRepo): Response
    {
        $criteria = new SearchCriteria();
        
        // Pré-sélection du groupe si passé en paramètre
        $groupId = $request->query->get('groupId');
        if ($groupId) {
            $group = $groupRepo->find($groupId);
            if ($group) {
                $criteria->setCriteriaGroup($group);
            }
        }
        
        $form = $this->createForm(SearchCriteriaType::class, $criteria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($criteria);
            $entityManager->flush();

            $this->addFlash('success', 'Critère de recherche créé avec succès.');

            return $this->redirectToRoute('admin_criteria_index');
        }

        return $this->render('admin/criteria/new_criteria.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}