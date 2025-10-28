<?php

namespace App\Controller\Admin;

use App\Service\ChimicalCriteriaFormFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ChimicalMakerController extends AbstractController
{
    #[Route('/admin/chimical/maker', name: 'app_admin_chimical_maker')]
    public function maker(ChimicalCriteriaFormFactory $chimicalCriteriaFormFactory, Request $request): Response
    {
        $form = $chimicalCriteriaFormFactory->makeForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            dd($form->getData(), $request->request->all());
        }
        return $this->render('admin/chimical/maker/index.html.twig', [
            'form' => $form,
        ]);
    }
}
