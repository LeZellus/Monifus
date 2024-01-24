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
        $totalPendingPrice = 0;
        $totalPendingProfit = 0;

        foreach ($sales as $sale) {
            $totalBuyPrice += $sale->getBuyPrice();
            $totalSellPrice += $sale->getSellPrice();

            if ($sale->isIsSell()) { // Seulement si la vente a été effectuée
                $profit = $totalSellPrice - $totalBuyPrice;
                $percentProfit = $profit / $totalBuyPrice * 100;
            }
            if (!$sale->isIsSell()) { // Seulement si la vente n'a pas été effectuée
                $totalPendingPrice += $sale->getSellPrice();
            }
        }

        $taxe = $totalSellPrice * 0.01;

        return $this->render('sale/index.html.twig', [
            'sales' => $sales,
            'totalBuyPrice' => $totalBuyPrice,
            'totalSellPrice' => $totalSellPrice,
            'profit' => $profit,
            'percentProfit' => $percentProfit,
            'totalPendingPrice' => $totalPendingPrice,
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

    #[Route('/vente/editer/{id}', name: 'app_sale_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, Sale $sale): Response
    {
        $form = $this->createForm(SaleType::class, $sale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('app_sale', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sale/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/vente/supprimer/{id}', name: 'app_sale_delete')]
    public function delete(Request $request, EntityManagerInterface $entityManager, Sale $sale): Response
    {
        // Créer et vérifier un "token CSRF" pour éviter les attaques CSRF
        if ($this->isCsrfTokenValid('delete'.$sale->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sale);
            $entityManager->flush();
        }


        // Redirection vers une autre page après la suppression
        return $this->redirectToRoute('app_sale');
    }
}
