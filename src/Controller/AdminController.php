<?php

namespace App\Controller;

use App\Repository\SaleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{

    #[Route('/admin', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(SaleRepository $saleRepository): Response
    {
        $sales = $saleRepository->findTopSalesByBuySellRatio();

        return $this->render('admin/index.html.twig', [
            'sales' => $sales,
        ]);
    }
}
