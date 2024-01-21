<?php

namespace App\Controller;

use App\Repository\MonitorRepository;
use App\Repository\ResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function show(int $id,UserInterface $user, MonitorRepository $monitorRepository, ResourceRepository $resourceRepository, ChartBuilderInterface $chartBuilder): Response
    {
        $resource = $resourceRepository->find($id);

        if (!$resource) {
            throw $this->createNotFoundException('La ressource demandée n\'existe pas.');
        }

        $monitors = $monitorRepository->findBy(['resource' => $id], ['id' => 'ASC']);

        // Préparation des données pour les graphiques
        $labels = range(1, count($monitors));

        $dataPer1 = array_map(fn($monitor) => $monitor->getPricePer1(), $monitors);
        $dataPer10 = array_map(fn($monitor) => $monitor->getPricePer10(), $monitors);
        $dataPer100 = array_map(fn($monitor) => $monitor->getPricePer100(), $monitors);

        // Créer les graphiques
        $chartPer1 = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chartPer1 = $this->createLineChart($chartBuilder, $labels, $dataPer1, 'Graphique pour 1', 'rgba(255, 99, 132, 0.5)');

        $chartPer10 = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chartPer10 = $this->createLineChart($chartBuilder, $labels, $dataPer10, 'Graphique pour 10', 'rgba(54, 162, 235, 0.5)');

        $chartPer100 = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chartPer100 = $this->createLineChart($chartBuilder, $labels, $dataPer100, 'Graphique pour 100', 'rgba(75, 192, 192, 0.5)');

        $combinedChart = $this->createCombinedChart($chartBuilder, $labels, $dataPer1, $dataPer10, $dataPer100);

        $averages = $monitorRepository->findMonitorAveragesByUser($user);

        return $this->render('resource/show.html.twig', [
            'resource' => $resource,
            'chartPer1' => $chartPer1,
            'chartPer10' => $chartPer10,
            'chartPer100' => $chartPer100,
            'combinedChart' => $combinedChart,
            'averages' => $averages,
        ]);
    }

    private function createLineChart(ChartBuilderInterface $chartBuilder, array $labels, array $data, string $title, string $backgroundColor): Chart {
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => $title,
                    'backgroundColor' => $backgroundColor,
                    'borderColor' => $backgroundColor,
                    'data' => $data,
                    'fill' => false,
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['beginAtZero' => true]],
                ],
            ],
            'responsive' => true,
        ]);

        return $chart;
    }

    private function createCombinedChart(ChartBuilderInterface $chartBuilder, array $labels, array $dataPer1, array $dataPer10, array $dataPer100): Chart {
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Moyenne de prix par moniteur pour le lot 1',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.5)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'data' => $dataPer1,
                    'fill' => false,
                ],
                [
                    'label' => 'Moyenne de prix par moniteur pour le lot 10',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'data' => $dataPer10,
                    'fill' => false,
                ],
                [
                    'label' => 'Moyenne de prix par moniteur pour le lot 100',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.5)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'data' => $dataPer100,
                    'fill' => false,
                ]
            ],
        ]);

        $chart->setOptions([
            // Options de configuration du graphique, par exemple :
            'scales' => [
                'yAxes' => [['ticks' => ['beginAtZero' => true]]],
            ],
            'responsive' => true,
        ]);

        return $chart;
    }
}
