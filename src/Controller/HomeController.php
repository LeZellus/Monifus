<?php

namespace App\Controller;

use App\Repository\ResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CacheInterface $cache): Response
    {
//        $datasCache = $cache->get('datasCache', function (ResourceRepository $resourceRepository){
//            return $resourceRepository->findAll();
//        });

        return $this->render('home/home.html.twig');
    }
}
