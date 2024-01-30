<?php

namespace App\Controller;

use App\Repository\MonitorRepository;
use App\Repository\ResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ResourceController extends AbstractController
{
    #[Route('/resource', name: 'app_resource')]
    public function index(UserInterface $user, MonitorRepository $monitorRepository): Response
    {
        $user = $this->getUser();
        $averages = $monitorRepository->findMonitorAveragesByUser($user);

        return $this->render('resource/index.html.twig', [
            'averages' => $averages,
        ]);
    }

    #[Route('/resource/{id}', name: 'app_resource_show')]
    public function show(int $id, ResourceRepository $resourceRepository): Response
    {
       $resource = $resourceRepository->find($id);

       if(!$resource){
           throw $this->createNotFoundException('La ressource demandée n\'existe pas');
       }

       return $this->render('resource/show.html.twig', [
           'resource' => $resource,
       ]);
    }

    #[Route('/resource/{id}/data', name: 'app_resource_data')]
    public function getDataForGraph(int $id, MonitorRepository $monitorRepository, Request $request): Response
    {
        // Récupération de la période depuis la requête
        $period = $request->query->get('period', 'year'); // La valeur par défaut est 'year'


        // Calculer la plage de dates en fonction de la période
        $endDate = new \DateTimeImmutable('now');
        $startDate = match ($period) {
            'day' => (new \DateTimeImmutable())->modify('-1 day')->setTime(0, 0, 0),
            'week' => (new \DateTimeImmutable())->modify('-1 week')->setTime(0, 0, 0),
            'month' => (new \DateTimeImmutable())->modify('-1 month')->setTime(0, 0, 0),
            default => (new \DateTimeImmutable())->modify('-1 year')->setTime(23, 59, 59),
        };

        // Récupérer les données du moniteur pour la période donnée
        $user = $this->getUser();
        $monitors = $monitorRepository->findMonitorsByPeriod($user, $id, $startDate, $endDate);

        // Préparation des données pour le graphique
        $dataForGraph = array_map(function ($monitor) {
            return [
                'x' => $monitor['createdAt'], // Assurez-vous que cette propriété correspond à votre entité Monitor
                'y' => $monitor["pricePer1"] // Remplacez par la méthode appropriée pour obtenir le prix
            ];
        }, $monitors);

        return $this->json($dataForGraph);
    }
}
