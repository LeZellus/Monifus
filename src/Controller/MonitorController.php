<?php

namespace App\Controller;

use App\Entity\Resource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Monitor;
use App\Form\MonitorType;
use App\Repository\MonitorRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/monitor')]
class MonitorController extends AbstractController
{
    #[Route('/', name: 'app_monitor_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); // Assurez-vous que l'authentification est configurée
        $resources = $entityManager->getRepository(Resource::class)->findResourcesWithMonitors($user);

        return $this->render('monitor/index.html.twig', [
            'resources' => $resources,
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
            //Peut-être à supprimer
            $monitor->setCreatedAt(new \DateTimeImmutable());
            $monitorRepository->save($monitor, true);

            return $this->redirectToRoute('app_monitor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('monitor/new.html.twig', [
            'monitor' => $monitor,
            'form' => $form,
        ]);
    }

    #[Route('/{resourceId}', name: 'monitors_for_resource', methods: ['GET'])]
    public function monitorsForResource(EntityManagerInterface $entityManager, int $resourceId): Response
    {
        $user = $this->getUser();
        $monitors = $entityManager->getRepository(Monitor::class)->findBy([
            'user' => $user,
            'resource' => $resourceId
        ]);

        return $this->render('monitor/show.html.twig', [
            'monitors' => $monitors,
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
    public function deleteMonitor(int $id, MonitorRepository $monitorRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $monitor = $monitorRepository->find($id);

        if (!$monitor) {
            return $this->json(['success' => false, 'message' => 'Moniteur non trouvé']);
        }

        $resourceId = $monitor->getResource()->getId();
        $entityManager->remove($monitor);
        $entityManager->flush();

        // Calculez le nombre de moniteurs restants
        $monitorCount = $monitorRepository->countByResource($resourceId);

        return $this->json(['success' => true, 'monitorCount' => $monitorCount, 'resourceId' => $resourceId]);
    }

}
