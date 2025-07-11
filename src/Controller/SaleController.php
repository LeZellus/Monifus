<?php

namespace App\Controller;

use App\Entity\Sale;
use App\Form\SaleType;
use App\Repository\SaleRepository;
use App\Service\BreadcrumbService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SaleController extends AbstractController
{
    private BreadcrumbService $breadcrumbService;
    private SaleRepository $saleRepository;

    public function __construct(BreadcrumbService $breadcrumbService, SaleRepository $saleRepository)
    {
        $this->breadcrumbService = $breadcrumbService;
        $this->saleRepository = $saleRepository;
    }
    #[Route('/vente', name: 'app_sale')]
    public function index(PaginatorInterface $paginator, Request $request, RequestStack $requestStack): Response
    {
        $user = $this->getUser();
        $isAdmin = $this->isGranted('ROLE_ADMIN');

        $session = $requestStack->getSession();


        $limit = $request->query->get('limit', $session->get('sale_pagination_limit', 50));

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $search = $request->query->get('search', '');
        $query = $this->saleRepository->findSalesByUserAndResourceName($user, $search, $isAdmin);
        $stats = $this->saleRepository->getSaleStatsForUserAndResourceSearch($user, $search);

        $page = $request->query->getInt('page', 1);

        $pagination = $paginator->paginate($query, $page, $limit);

        $session->set('sale_pagination_limit', $limit);

        $this->breadcrumbService->setBreadcrumbs("Ventes", '/sales');

        return $this->render('sale/index.html.twig', [
            'pagination' => $pagination,
            'stats' => $stats,
            'search' => $search,
            'limit' => $limit,
            'noSales' => empty($query), // Vérifie s'il n'y a aucun Sale
            'currentUser' => $user,
        ]);
    }

    #[Route('/vente/nouveau', name: 'app_sale_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sale = new Sale();
        $form = $this->createForm(SaleType::class, $sale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sale->setUser($this->getUser());
            $entityManager->persist($sale);
            $entityManager->flush();

            return $this->redirectToRoute('app_sale', [], Response::HTTP_SEE_OTHER);
        }

        $this->breadcrumbService->setBreadcrumbs("Ventes", '/vente');
        $this->breadcrumbService->setBreadcrumbs("Nouveau", '');

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

        $this->breadcrumbService->setBreadcrumbs("Ventes", '/vente');
        $this->breadcrumbService->setBreadcrumbs("Editer", '');

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

    #[Route('/vente/copier/{id}', name: 'app_sale_copy', methods: ['POST'])]
    public function copy(Request $request, EntityManagerInterface $entityManager, Sale $sale): Response
    {
        $newSale = clone $sale; // Clone l'objet Sale existant

        // Vous pouvez modifier ici les propriétés nécessaires du clone, par exemple :
        // $newSale->setId(null); // Réinitialise l'ID pour créer un nouvel enregistrement

        $entityManager->persist($newSale);
        $entityManager->flush();

        return $this->redirectToRoute('app_sale_edit', ['id' => $newSale->getId()]);
    }
}
