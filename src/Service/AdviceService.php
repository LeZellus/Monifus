<?php

namespace App\Service;

class AdviceService
{
    private $advices = [
        "Familiarisez-vous avec toutes les classes pour mieux comprendre leurs forces et faiblesses.",
        "Participez régulièrement à des donjons pour gagner de l'expérience et des ressources précieuses.",
        "Rejoignez une guilde active pour bénéficier d'aide et de conseils.",
        "Soyez prudent avec les transactions entre joueurs pour éviter les arnaques.",
        "Utilisez les forums et les guides en ligne pour rester informé des dernières stratégies.",
        "N'oubliez pas de vous équiper d'objets adéquats pour votre niveau et style de jeu.",
        "Participez aux événements saisonniers pour des récompenses exclusives.",
        "Améliorez régulièrement votre équipement pour rester compétitif.",
        "Pratiquez le PvP pour développer vos compétences en combat.",
        "Établissez des objectifs à court et à long terme pour rester motivé.",
        "Apprenez à créer des objets utiles via les métiers.",
        "Soyez actif dans la communauté pour établir des relations utiles.",
        "Mémorisez les emplacements des ressources clés pour une collecte efficace.",
        "Gardez un œil sur les mises à jour du jeu pour vous adapter rapidement aux changements.",
        "Variez vos activités en jeu pour éviter la monotonie.",
        "Soyez respectueux envers les autres joueurs pour maintenir une bonne ambiance de jeu.",
        "N'oubliez pas de vous amuser, après tout, c'est un jeu !",
        "Consultez votre profil pour mettre vos informations à jour !",
    ];

    public function getRandomAdvice(): string
    {
        return $this->advices[array_rand($this->advices)];
    }
}