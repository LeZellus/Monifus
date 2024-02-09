<?php

namespace App\Controller;

use App\Repository\MonitorRepository;
use App\Repository\ResourceRepository;
use App\Service\BreadcrumbService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ResourceController extends AbstractController
{
    private BreadcrumbService $breadcrumbService;

    public function __construct(BreadcrumbService $breadcrumbService)
    {
        $this->breadcrumbService = $breadcrumbService;
    }

    #[Route('/resource', name: 'app_resource')]
    public function index(UserInterface $user, MonitorRepository $monitorRepository): Response
    {
        $user = $this->getUser();
        $averages = $monitorRepository->findMonitorAveragesByUser($user);

        $this->breadcrumbService->setBreadcrumbs("Ressources", "");

        return $this->render('resource/index.html.twig', [
            'averages' => $averages,
        ]);
    }

    #[Route('/resource/{id}', name: 'app_resource_show')]
    public function show(int $id, ResourceRepository $resourceRepository): Response
    {
       /*$resource = $resourceRepository->find($id);

       if(!$resource){
           throw $this->createNotFoundException('La ressource demandée n\'existe pas');
       }

       return $this->render('resource/show.html.twig', [
           'resource' => $resource,
       ]);*/

        return $this->render('home/coming_soon.html.twig');
    }
}
