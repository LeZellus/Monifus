<?php

namespace App\Controller;

use App\Entity\Monster;
use App\Repository\RecordRepository;
use App\Service\BreadcrumbService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MonsterController extends AbstractController
{
    private BreadcrumbService $breadcrumbService;

    public function __construct(BreadcrumbService $breadcrumbService)
    {
        $this->breadcrumbService = $breadcrumbService;
    }

    #[Route('/monstre/{id}/records', name: 'app_monster_show')]
    public function show(Monster $monster, RecordRepository $recordRepository): Response
    {
        $records = $recordRepository->findRecordsWithUserByMonster($monster->getId());
        $monsterName = $monster->getName();

        $this->breadcrumbService->setBreadcrumbs("Records", "/records");
        $this->breadcrumbService->setBreadcrumbs($monsterName, "");

        return $this->render('monster/show.html.twig', [
            'monster' => $monster,
            'records' => $records,
        ]);
    }
}
