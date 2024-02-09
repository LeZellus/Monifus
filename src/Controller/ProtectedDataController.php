<?php

namespace App\Controller;

use App\Service\BreadcrumbService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProtectedDataController extends AbstractController
{
    private BreadcrumbService $breadcrumbService;

    public function __construct(BreadcrumbService $breadcrumbService)
    {
        $this->breadcrumbService = $breadcrumbService;
    }

    #[Route('/protected/data', name: 'app_protected_data')]
    public function index(): Response
    {
        $this->breadcrumbService->setBreadcrumbs("Protection des données", "");

        return $this->render('protected_data/index.html.twig');
    }
}
