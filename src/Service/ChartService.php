<?php

namespace App\Service;

use App\Repository\PriceRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartService
{
    private ChartBuilderInterface $chartBuilder;

    public function __construct(ChartBuilderInterface $chartBuilder)
    {
        $this->chartBuilder = $chartBuilder;
    }

    public function getGraphConfigurations(Array $pricesData): array
    {
        // Préparation des labels et des données
        $labels = [];
        $datasets = [
            'dataOne' => [],
            'dataTen' => [],
            'dataHundred' => [],
            'avgPrices' => []
        ];

        foreach ($pricesData['details'] as $detail) {
            $labels[] = $detail['createdAt']->format('Y-m-d');
            $datasets['dataOne'][] = $detail['priceOne'];
            $datasets['dataTen'][] = $detail['priceTen'];
            $datasets['dataHundred'][] = $detail['priceHundred'];
        }

        // Calcul des moyennes pour chaque lot si nécessaire
        $aggregatedData = $pricesData['aggregated'];
        $datasets['avgPrices'] = [
            'Moyenne lot de 1' => $aggregatedData['avgPriceOne'] ?? 0,
            'Moyenne lot de 10' => $aggregatedData['avgPriceTen'] ?? 0,
            'Moyenne lot de 100' => $aggregatedData['avgPriceHundred'] ?? 0,
        ];

        // Configuration de chaque graphique
        return [
            'graphOne' => [
                'chart' => $this->createChart($labels, $datasets['dataOne'], 'Prix lot de 1', 'Evolution prix par lot de 1'),
                'title' => 'Evolution prix par lot de 1'
            ],
            'graphTen' => [
                'chart' => $this->createChart($labels, $datasets['dataTen'], 'Prix lot de 10', 'Evolution prix par lot de 10'),
                'title' => 'Evolution prix par lot de 10'
            ],
            'graphHundred' => [
                'chart' => $this->createChart($labels, $datasets['dataHundred'], 'Prix lot de 100', 'Evolution prix par lot de 100'),
                'title' => 'Evolution prix par lot de 100'
            ],
            'graphAvg' => [
                'chart' => $this->createChart(array_keys($datasets['avgPrices']), array_values($datasets['avgPrices']), 'Moyennes', 'Evolution moyennes des prix', Chart::TYPE_BAR),
                'title' => 'Evolution moyennes des prix'
            ],
        ];
    }

    private function createChart(array $labels, array $data, string $label, string $graphName, string $type = Chart::TYPE_LINE): Chart
    {
        $chart = $this->chartBuilder->createChart($type);

        // Configuration des datasets en fonction du type de graphique
        $datasets = [
            'label' => $label,
            'data' => $data,
        ];

        $datasets['borderColor'] = '#FF7828';
        $datasets['pointBackgroundColor'] = '#FF7828';
        $datasets['pointBorderColor'] = '#fff';
        $datasets['borderWidth'] = 1;
        $datasets['pointHoverBackgroundColor'] = '#fff';
        $datasets['pointHoverBorderColor'] = '#FF7828';

        $chart->setData([
            'labels' => $labels,
            'datasets' => [$datasets],
        ]);

        // Configuration des options du graphique
        $chart->setOptions([
            'legend' => [
                'display' => true, // Affiche la légende
                // Autres options de légende
            ],
            'title' => [
                'display' => true,
                'text' => $graphName, // Utilisation de la variable pour le titre
            ],
        ]);

        return $chart;
    }
}