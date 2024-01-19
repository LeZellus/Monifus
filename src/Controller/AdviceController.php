<?php

namespace App\Controller;

use App\Service\AdviceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdviceController extends AbstractController
{
    #[Route('/advice', name: 'app_advice')]
    public function index(AdviceService $adviceService): Response
    {
        return new Response($adviceService->getRandomAdvice());
    }
}
