<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BlasonController extends AbstractController
{
    #[Route('/blason', name: 'app_blason')]
    public function index(): Response
    {
        $backDir = $this->getParameter('kernel.project_dir') . '/public/blason/back';
        $symbolDir = $this->getParameter('kernel.project_dir') . '/public/blason/symbol';

        $backs = [];
        for ($i = 1; $i <= 34; $i++) {
            $backPath = $backDir . '/' . $i;
            if (file_exists($backPath)) {
                // Supposons que chaque dossier contient border.svg et background.svg
                $backs[$i] = [
                    'border' => 'blason/back/' . $i . '/border.svg',
                    'background' => 'blason/back/' . $i . '/background.svg'
                ];
            }
        }

        $symbols = [];
        for ($i = 1; $i <= 499; $i++) {
            $symbolPath = $symbolDir . '/' . $i . '.svg';
            if (file_exists($symbolPath)) {
                $symbols[$i] = 'blason/symbol/' . $i . '.svg';
            }
        }

        return $this->render('blason/index.html.twig', [
            'backs' => $backs,
            'symbols' => $symbols,
        ]);
    }
}
