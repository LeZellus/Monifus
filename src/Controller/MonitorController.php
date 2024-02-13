<?php

namespace App\Controller;

use App\Entity\Price;
use App\Entity\Resource;
use App\Entity\User;
use App\Service\BreadcrumbService;
use App\Service\ExtractYoutubeUrlService;
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
    private Security $security;
    private ExtractYoutubeUrlService $extractYoutubeUrlService;

    public function __construct(BreadcrumbService $breadcrumbService, Security $security, ExtractYoutubeUrlService $extractYoutubeUrlService)
    {
        $this->breadcrumbService = $breadcrumbService;
        $this->security = $security;
    }
    #[Route('/', name: 'app_monitor_index', methods: ['GET'])]
    public function index(MonitorRepository $monitorRepository, Security $security): Response
    {
        $user = $security->getUser();

        $monitorsWithAvgPrices  = $monitorRepository->findByUserWithResourceAndPrices($user->getId());

        $this->breadcrumbService->setBreadcrumbs("Moniteurs", "");

        // render the view with the monitors
        return $this->render('monitor/index.html.twig', [
            'monitorsWithAvgPrices' => $monitorsWithAvgPrices,
        ]);
    }

    #[Route('/nouveau', name: 'app_monitor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MonitorRepository $monitorRepository, EntityManagerInterface $entityManager): Response
    {
        $monitor = new Monitor();
        $form = $this->createForm(MonitorType::class, $monitor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedResource = $monitor->getResource();

            // Vérifier si un Monitor avec cette ressource existe déjà pour cet utilisateur
            $existingMonitor = $monitorRepository->findOneBy([
                'user' => $this->getUser(),
                'resource' => $selectedResource
            ]);

            if (!$existingMonitor) {
                // Créer un nouveau Monitor
                $monitor->setUser($this->getUser());
                $entityManager->persist($monitor);
            } else {
                // Utiliser le Monitor existant
                $monitor = $existingMonitor;
            }

            // Traiter les objets Price soumis via le formulaire
            foreach ($form->get('prices')->getData() as $price) {
                $price->setMonitor($monitor);
                $entityManager->persist($price);
            }

            // Flush pour enregistrer les changements
            $entityManager->flush();

            // Rediriger vers la liste des Monitors
            return $this->redirectToRoute('app_monitor_index');
        }

        $this->breadcrumbService->setBreadcrumbs("Moniteurs", "/monitor");
        $this->breadcrumbService->setBreadcrumbs("Nouveau", "");

        return $this->render('monitor/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/search', name: 'app_monitor_search', methods: ['GET', 'POST'])]
    public function search(Request $request, MonitorRepository $monitorRepository): Response
    {
        $searchTerm = $request->query->get('search');

        // Utiliser MonitorRepository pour rechercher les moniteurs
        $monitorsWithAvgPrices = $monitorRepository->findBySearchTerm($searchTerm);

        $this->breadcrumbService->setBreadcrumbs("Moniteurs", "");

        return $this->render('monitor/index.html.twig', [
            'monitorsWithAvgPrices' => $monitorsWithAvgPrices,
        ]);
    }

    #[Route('/{id}', name: 'app_monitor_show', methods: ['GET', 'POST'])]
    public function show(int $id, MonitorRepository $monitorRepository): Response
    {
        $monitor = $monitorRepository->findOneByIdWithResourceAndPrices($id);
        $monitorAggregates = $monitorRepository->getMonitorPriceAggregates($id);

        $user = $this->security->getUser();

        // Vérifier si l'utilisateur actuel est le propriétaire du moniteur
        if (!$monitor || $monitor->getUser() !== $user) {
            throw $this->createNotFoundException('Ce moniteur n\'existe pas ou vous n\'avez pas le droit de le consulter.');
        }

        $this->breadcrumbService->setBreadcrumbs("Moniteurs", "/monitor");
        $this->breadcrumbService->setBreadcrumbs($monitor->getResource()->getName(), "");

        return $this->render('monitor/show.html.twig', [
            'monitor' => $monitor,
            'aggregates' => $monitorAggregates,
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
