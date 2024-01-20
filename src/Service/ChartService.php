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

    public function generateChart(): Chart
    {
        // Ici, vous pouvez utiliser la bibliothèque Chart.js pour générer un graphique en fonction des données fournies.
        // Vous pouvez personnaliser cette méthode en fonction de vos besoins spécifiques.

        // Exemple de création d'un objet Chart
        $chartData = [
            'type' => 'bar', // Type de graphique (bar, line, etc.)
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