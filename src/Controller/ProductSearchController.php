<?php
// src/Controller/ProductSearchController.php

namespace App\Controller;

use App\DTO\GroupedSearchDTO;
use App\Form\SearchCriteriaType;
use App\Repository\ProductRepository;
use App\Service\DynamicSearchService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProductSearchController extends AbstractController
{
    #[Route('/search/products', name: 'product_search')]
    public function searchProducts(
        Request $request,
        DynamicSearchService $searchService,
        ProductRepository $productRepository
    ): Response {
        // Créer le formulaire de recherche
        $form = $searchService->createSearchForm(SearchCriteriaType::class, ['product_filters', 'date_filters']);

        $form->handleRequest($request);
        $results = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            
            // Créer le DTO de recherche
            $searchDto = $searchService->createSearchDto($formData);
            
            // Appliquer les critères de recherche
            $queryBuilder = $productRepository->createQueryBuilder('p');
            $searchService->applySearchCriteria($queryBuilder, $searchDto, 'p');
            
            $results = $queryBuilder->getQuery()->getResult();
        }

        return $this->render('product/search.html.twig', [
            'form' => $form->createView(),
            'results' => $results,
        ]);
    }
}