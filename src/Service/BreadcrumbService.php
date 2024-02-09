<?php

namespace App\Service;

use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class BreadcrumbService
{
    private Breadcrumbs $breadcrumbs;

    public function __construct(Breadcrumbs $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
        $this->setHome();
    }

    public function setHome(): void
    {
        $this->breadcrumbs->addItem('Accueil', '/');
    }

    public function setBreadcrumbs(string $label, string $route = null): void
    {
        $this->breadcrumbs->addItem($label, $route);
    }
}