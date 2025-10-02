<?php

namespace App\Controller\Admin;

use App\Entity\Unit;
use App\Form\UnitType;
use App\Repository\UnitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/unit')]
final class AdminUnitController extends AbstractController
{
    #[Route(name: 'app_admin_unit_index', methods: ['GET'])]
    public function index(UnitRepository $unitRepository): Response
    {
        return $this->render('admin/unit/index.html.twig', [
            'units' => $unitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_unit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $unit = new Unit();
        $form = $this->createForm(UnitType::class, $unit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($unit);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_unit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/unit/new.html.twig', [
            'unit' => $unit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_unit_show', methods: ['GET'])]
    public function show(Unit $unit): Response
    {
        return $this->render('admin/unit/show.html.twig', [
            'unit' => $unit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_unit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Unit $unit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UnitType::class, $unit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_unit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/unit/edit.html.twig', [
            'unit' => $unit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_unit_delete', methods: ['POST'])]
    public function delete(Request $request, Unit $unit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$unit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($unit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_unit_index', [], Response::HTTP_SEE_OTHER);
    }
}
