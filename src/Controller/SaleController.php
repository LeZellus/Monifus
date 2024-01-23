<?php

namespace App\Controller;

use App\Entity\Sale;
use App\Form\SaleType;
use App\Repository\SaleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SaleController extends AbstractController
{
    #[Route('/vente', name: 'app_sale')]
    public function index(SaleRepository $saleRepository): Response
    {
        $sales = $saleRepository->findAll();
        $totalBuyPrice = 0;
        $totalSellPrice = 0;

        foreach ($sales as $sale) {
            $totalBuyPrice += $sale->getBuyPrice();
            if ($sale->isIsSell()) { // Seulement si la vente a été effectuée
                $totalSellPrice += $sale->getSellPrice();
            }
        }

        $taxe = $totalBuyPrice * 0.01;

        return $this->render('sale/index.html.twig', [
            'sales' => $sales,
            'totalBuyPrice' => $totalBuyPrice,
            'totalSellPrice' => $totalSellPrice,
            'taxe' => $taxe,
            'noSales' => empty($sales), // Vérifie s'il n'y a aucun Sale
        ]);
    }

    #[Route('/vente/nouveau', name: 'app_sale_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sale = new Sale();
        $form = $this->createForm(SaleType::class, $sale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($sale);
            $entityManager->flush();

            return $this->redirectToRoute('app_sale', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sale/new.html.twig', [
            'form' => $form,
        ]);
    }
}
