<?php

namespace App\Controller;

use App\Entity\Resource;
use App\Service\CallApiService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiToDatabaseController extends AbstractController
{
    #[Route('/apitodatabase', name: 'app_api_to_database')]
    public function index(CallApiService $apiService, EntityManagerInterface $entityManager): Response
    {
        $results = $apiService->getDofapiData();

        foreach($results as $result) {
            $resource = new Resource();
            $resource->setName($result["name"]);
            $resource->setAnkamaId($result["ankamaId"]);
            $resource->setDescription($result["description"]);
            $resource->setImgUrl($result["imgUrl"]);
            $resource->setLevel($result["level"]);
            $resource->setIsImportant(false);

            $entityManager->persist($resource);
            $entityManager->flush();
        }

        return $this->render('api_to_database/index.html.twig', [
            'controller_name' => 'ApiToDatabaseController',
        ]);
    }
}
