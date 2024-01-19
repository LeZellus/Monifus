<?php

namespace App\Service;

class AdviceService
{
    private $advices = [
        "Buvez plus d'eau pour rester hydraté.",
        "Prenez des pauses courtes mais fréquentes pour rester productif.",
        "Organisez votre espace de travail pour améliorer l'efficacité.",
        // Ajoutez d'autres conseils ici
    ];

    public function getRandomAdvice(): string
    {
        return $this->advices[array_rand($this->advices)];
    }
}