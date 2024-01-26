<?php

namespace App\Controller;

use App\Entity\Monster;
use App\Repository\RecordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MonsterController extends AbstractController
{
    #[Route('/monstre/{id}/records', name: 'app_monster_show')]
    public function show(Monster $monster, RecordRepository $recordRepository): Response
    {
        $records = $recordRepository->findRecordsWithUserByMonster($monster->getId());

        return $this->render('monster/show.html.twig', [
            'monster' => $monster,
            'records' => $records,
        ]);
    }
}
