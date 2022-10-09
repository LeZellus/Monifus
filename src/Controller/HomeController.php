<?php

namespace App\Controller;

use App\Repository\ResourceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Cache\CacheInterface;

class HomeController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'app_home')]
    public function index(CacheInterface $cache): Response
    {
        $datasCache = $cache->get('datasCache', function (ResourceRepository $resourceRepository){
            return $resourceRepository->findAll();
        });

        if ($this->security->isGranted('ROLE_SALES_ADMIN')) {
            $salesData['top_secret_numbers'] = rand();
        }

        return $this->render('home/home.html.twig', [
            "datas" => $datasCache
        ]);
    }
}
