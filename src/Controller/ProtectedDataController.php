<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProtectedDataController extends AbstractController
{
    #[Route('/protected/data', name: 'app_protected_data')]
    public function index(): Response
    {
        return $this->render('protected_data/index.html.twig');
    }
}
