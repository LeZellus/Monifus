<?php

namespace App\Controller;

use App\Entity\Monster;
use App\Entity\Record;
use App\Form\RecordType;
use App\Repository\MonsterRepository;
use App\Repository\RecordRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecordController extends AbstractController
{
    #[Route('/record', name: 'app_record')]
    public function index(MonsterRepository $monsterRepository, RecordRepository $recordRepository): Response
    {
        $monsters = $monsterRepository->findMonstersWithRecords();
        $bestRecords = $recordRepository->findBestTimeForAllMonsters();
        $recordCounts = $monsterRepository->countRecordsForEachMonster();

        return $this->render('record/index.html.twig', [
            'monsters' => $monsters,
            'bestRecords' => $bestRecords,
            'recordCounts' => $recordCounts,
        ]);
    }
    #[Route('/record/nouveau', name: 'app_record_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $record = new Record();
        $form = $this->createForm(RecordType::class, $record);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $record->setIsApproved(false);
            $record->setUser($this->getUser());
            $entityManager->persist($record);
            $entityManager->flush();

            // Rediriger vers une page appropriée
            return $this->redirectToRoute('app_record'); // Modifiez la route selon vos besoins
        }

        return $this->render('record/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/record/editer/{id}', name: 'app_record_edit')]
    public function edit(Monster $monster, EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(RecordType::class, $monster);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('app_record', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('record/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('record/{id}', name: 'app_record_show')]
    public function show(int $id, RecordRepository $recordRepository): Response
    {
        $record = $recordRepository->find($id);

        return $this->render('record/show.html.twig', [
            'record' => $record,
        ]);
    }

    #[Route('/record/supprimer/{id}', name: 'app_record_delete')]
    public function delete(Monster $monster, RecordRepository $recordRepository): Response
    {
        $records = $recordRepository->findRecordsWithUserByMonster($monster);
        return $this->render('monster/index.html.twig', [
            'monster' => $monster,
            'records' => $monster->getRecords(),
        ]);
    }

}
