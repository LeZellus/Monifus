<?php

namespace App\Controller;

use App\Entity\Price;
use App\Form\PriceType;
use App\Repository\PriceRepository;
use App\Service\BreadcrumbService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/price')]
class PriceController extends AbstractController
{
    private BreadcrumbService $breadcrumbService;
    private Security $security;

    public function __construct(BreadcrumbService $breadcrumbService, Security $security)
    {
        $this->breadcrumbService = $breadcrumbService;
        $this->security = $security;
    }

    #[Route('/', name: 'app_price_index', methods: ['GET'])]
    public function index(PriceRepository $priceRepository, Security $security): Response
    {
        $user = $security->getUser();

        $monitorsWithAvgPrices  = $priceRepository->findByUserWithResourceAndPrices($user->getId());

        $this->breadcrumbService->setBreadcrumbs("Moniteurs", "");

        // render the view with the monitors
        return $this->render('price/index.html.twig', [
            'monitorsWithAvgPrices' => $monitorsWithAvgPrices,
        ]);
    }

    #[Route('/nouveau', name: 'app_price_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $price = new Price();
        $form = $this->createForm(PriceType::class, $price);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $price->setUser($this->getUser());
            $entityManager->persist($price);
            $entityManager->flush();

            // Rediriger vers la liste des Monitors
            return $this->redirectToRoute('app_price_index');
        }

        $this->breadcrumbService->setBreadcrumbs("Moniteurs", "/monitor");
        $this->breadcrumbService->setBreadcrumbs("Nouveau", "");

        return $this->render('price/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/search', name: 'app_price_search', methods: ['GET', 'POST'])]
    public function search(Request $request, PriceRepository $priceRepository): Response
    {
        $searchTerm = $request->query->get('search');

        $monitorsWithAvgPrices = $priceRepository->findBySearchTerm($searchTerm);

        $this->breadcrumbService->setBreadcrumbs("Moniteurs", "");

        return $this->render('price/index.html.twig', [
            'monitorsWithAvgPrices' => $monitorsWithAvgPrices,
        ]);
    }

    #[Route('/{id}', name: 'app_price_show', methods: ['GET', 'POST'])]
    public function show(int $id, PriceRepository $priceRepository): Response
    {
        $user = $this->security->getUser();

        // Récupère tous les prix pour l'utilisateur actuel et la ressource donnée
        $prices = $priceRepository->findBy([
            'User' => $user->getId(),
            'Resource' => $id
        ]);

        $priceCount = count($prices);
        $avgPriceOne = $avgPriceTen = $avgPriceHundred = 0;

        if ($priceCount > 0) {
            $avgPriceOne = array_sum(array_map(fn($price) => $price->getPriceOne(), $prices)) / $priceCount;
            $avgPriceTen = array_sum(array_map(fn($price) => $price->getPriceTen(), $prices)) / $priceCount;
            $avgPriceHundred = array_sum(array_map(fn($price) => $price->getPriceHundred(), $prices)) / $priceCount;
        }

        // render la vue avec les détails de la ressource
        return $this->render('price/show.html.twig', [
            'prices' => $prices,
            'avgPriceOne' => $avgPriceOne,
            'avgPriceTen' => $avgPriceTen,
            'avgPriceHundred' => $avgPriceHundred,
            'priceCount' => $priceCount
        ]);
    }
}
