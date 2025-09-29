<?php
// src/Controller/ProductController.php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductSearchType;
use App\DTO\ProductSearchCriteria;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $searchCriteria = new ProductSearchCriteria();
        
        // Récupérer les valeurs pour les filtres
        $filterValues = $productRepository->getFilterValues();

        $form = $this->createForm(ProductSearchType::class, $searchCriteria, [
            'categories' => array_combine($filterValues['categories'], $filterValues['categories']),
            // 'tags' => array_combine($filterValues['tags'], $filterValues['tags']),
        ]);

        $form->handleRequest($request);

        // Gestion du bouton reset
        if ($form->get('reset')->isClicked()) {
            return $this->redirectToRoute('app_products');
        }

        $results = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $results = $productRepository->findBySearchCriteria($searchCriteria);
        } else {
            // Résultats par défaut
            $results = $productRepository->findBySearchCriteria($searchCriteria);
        }

        return $this->render('product/index.html.twig', [
            'form' => $form->createView(),
            'results' => $results,
            'filterValues' => $filterValues,
        ]);
    }
}