<?php

namespace App\Controller;

use App\Service\BreadcrumbService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DocumentationController extends AbstractController
{
    private BreadcrumbService $breadcrumbService;

    public function __construct(BreadcrumbService $breadcrumbService)
    {
        $this->breadcrumbService = $breadcrumbService;
    }
    #[Route('/documentation', name: 'app_documentation')]
    public function index(): Response
    {
        $this->breadcrumbService->setBreadcrumbs("Documentation", "");

        return $this->render('documentation/index.html.twig');
    }
}
