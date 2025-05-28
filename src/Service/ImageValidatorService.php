<?php

namespace App\Service;

use Symfony\Component\Asset\Packages;

class ImageValidatorService
{
    private Packages $packages;
    private string $defaultImagePath;

    public function __construct(
        Packages $packages,
        string $defaultImagePath = 'uploads/default-item.png'
    ) {
        $this->packages = $packages;
        $this->defaultImagePath = $defaultImagePath;
    }

    public function validateAndGetImageUrl(?string $url): string
    {
        // Si l'URL est vide ou nulle, retourne l'image par défaut
        if (empty($url)) {
            return $this->getDefaultImageUrl();
        }

        // Validation basique de l'URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return $this->getDefaultImageUrl();
        }

        return $url;
    }

    private function getDefaultImageUrl(): string
    {
        return $this->packages->getUrl($this->defaultImagePath);
    }
}