<?php

namespace App\Controller;

use App\Repository\MonitorRepository;
use App\Repository\ResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ResourceController extends AbstractController
{
    #[Route('/resource', name: 'app_resource')]
    public function index(UserInterface $user, MonitorRepository $monitorRepository): Response
    {
        $user = $this->getUser();

        // Utilisez la méthode avec cache si implémentée
        // $averages = $monitorRepository->findMonitorAveragesByUserCached($user, $cache);

        // Si vous avez implémenté la pagination, ajustez en conséquence
        // $page = // déterminez la page actuelle
        $averages = $monitorRepository->findMonitorAveragesByUser($user);

        return $this->render('resource/index.html.twig', [
            'averages' => $averages,
        ]);
    }
}
