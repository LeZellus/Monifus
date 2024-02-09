<?php

namespace App\Controller;

use App\Service\BreadcrumbService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LegalMentionsController extends AbstractController
{
    private BreadcrumbService $breadcrumbService;

    public function __construct(BreadcrumbService $breadcrumbService)
    {
        $this->breadcrumbService = $breadcrumbService;
    }

    #[Route('/mentions/legales', name: 'app_legal_mentions')]
    public function index(): Response
    {
        $this->breadcrumbService->setHome();
        $this->breadcrumbService->setBreadcrumbs("Mentions légales", "");

        return $this->render('legal_mentions/index.html.twig');
    }
}
