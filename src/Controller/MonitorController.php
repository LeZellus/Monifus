<?php

namespace App\Controller;

use App\Entity\Resource;
use App\Entity\User;
use App\Service\BreadcrumbService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Monitor;
use App\Form\MonitorType;
use App\Repository\MonitorRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

#[Route('/monitor')]
class MonitorController extends AbstractController
{
    private BreadcrumbService $breadcrumbService;
    public function __construct(BreadcrumbService $breadcrumbService)
    {
        $this->breadcrumbService = $breadcrumbService;
    }
    #[Route('/', name: 'app_monitor_index', methods: ['GET'])]
    public function index(MonitorRepository $monitorRepository, Security $security): Response
    {
        $user = $security->getUser();

        $monitorsWithAvgPrices  = $monitorRepository->findByUserWithResourceAndPrices($user->getId());

        // render the view with the monitors
        return $this->render('monitor/index.html.twig', [
            'monitorsWithAvgPrices' => $monitorsWithAvgPrices,
        ]);
    }

    #[Route('/nouveau', name: 'app_monitor_new', methods: ['GET', 'POST'])]
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

        $this->breadcrumbService->setBreadcrumbs("Moniteurs", "/monitor");
        $this->breadcrumbService->setBreadcrumbs("Nouveau", "");

        return $this->render('monitor/new.html.twig', [
            'monitor' => $monitor,
            'form' => $form,
        ]);
    }

    #[Route('/monitors/search', name: 'app_monitor_search', methods: ['GET', 'POST'])]
    public function search(Request $request, MonitorRepository $monitorRepository): Response
    {
        $searchTerm = $request->query->get('search');

        // Utiliser MonitorRepository pour rechercher les moniteurs
        $monitorsWithAvgPrices = $monitorRepository->findBySearchTerm($searchTerm);

        return $this->render('monitor/index.html.twig', [
            'monitorsWithAvgPrices' => $monitorsWithAvgPrices,
        ]);
    }


    #[Route('/edit/{id}', name: 'app_monitor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Monitor $monitor, EntityManagerInterface $entityManager): Response
    {
        $type = $request->request->get('type');
        $value = $request->request->get('value');

        if (in_array($type, ['pricePer1', 'pricePer10', 'pricePer100'])) {
            $monitor->{'set'.ucfirst($type)}($value);
            $entityManager->flush();
            return $this->json(['success' => true]);
        }

        return $this->json(['success' => false]);
    }

    #[Route('/delete/{id}', name: 'app_monitor_delete', methods: ['DELETE', 'POST'])]
    public function deleteMonitor(): Response
    {
        return $this->render('app_home');
    }
}
