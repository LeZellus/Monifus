<?php

namespace App\Controller;

use App\Entity\Monitor;
use App\Form\MonitorType;
use App\Repository\MonitorRepository;
use App\Repository\StockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/monitor')]
class MonitorController extends AbstractController
{
    #[Route('/', name: 'app_monitor_index', methods: ['GET'])]
    public function index(MonitorRepository $monitorRepository, StockRepository $stockRepository): Response
    {
        $quantity = 1;
//        $monitors = $monitorRepository->getAverageMonitorsByStockByUserByQuantity($monitorRepository, $quantity);
            $monitors = $monitorRepository->getMonitorByResource($monitorRepository);
//        dd($monitorRepository->getMonitorByResource($monitorRepository));


//        $stocks = $stockRepository->findAll();

        return $this->render('monitor/index.html.twig', [
            'monitors' => $monitors
//            'stocks' => $stocks
        ]);
    }

    #[Route('/new', name: 'app_monitor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MonitorRepository $monitorRepository): Response
    {
        $monitor = new Monitor();
        $form = $this->createForm(MonitorType::class, $monitor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $monitor->setUser($this->getUser());
            $monitorRepository->save($monitor, true);

            return $this->redirectToRoute('app_monitor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('monitor/new.html.twig', [
            'monitor' => $monitor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_monitor_show', methods: ['GET'])]
    public function show(Monitor $monitor): Response
    {
        return $this->render('monitor/show.html.twig', [
            'monitor' => $monitor,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_monitor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Monitor $monitor, MonitorRepository $monitorRepository): Response
    {
        $form = $this->createForm(MonitorType::class, $monitor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $monitorRepository->save($monitor, true);

            return $this->redirectToRoute('app_monitor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('monitor/edit.html.twig', [
            'monitor' => $monitor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_monitor_delete', methods: ['POST'])]
    public function delete(Request $request, Monitor $monitor, MonitorRepository $monitorRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$monitor->getId(), $request->request->get('_token'))) {
            $monitorRepository->remove($monitor, true);
        }

        return $this->redirectToRoute('app_monitor_index', [], Response::HTTP_SEE_OTHER);
    }
}
