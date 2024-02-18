<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartService
{
    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function generateChart($data): Chart
    {
        $labels = []; // dates
        $dataPricesOne = []; // prix par lot de 1
        $dataPricesTen = []; // prix par lot de 10
        $dataPricesHundred = []; // prix par lot de 100

        $chartData = [
            'type' => 'line', // Type de graphique (bar, line, etc.)
            'data' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                'datasets' => [
                    [
                        'label' => 'Example Data',
                        'data' => [12, 19, 3, 5, 2],
                    ],
                ],
            ],
        ];

        return new Chart('bar', $chartData);
    }

}