<?php

namespace App\Controller\Admin;

use App\Service\ChimicalCriteriaFormFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ChimicalMakerController extends AbstractController
{
    #[Route('/admin/chimical/maker', name: 'app_admin_chimical_maker')]
    public function maker(ChimicalCriteriaFormFactory $chimicalCriteriaFormFactory): Response
    {
        $form = $chimicalCriteriaFormFactory->makeForm();
        return $this->render('admin/chimical/maker/index.html.twig', [
            'form' => $form,
        ]);
    }
}
