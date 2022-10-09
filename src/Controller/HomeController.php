<?php

namespace App\Controller;

use App\Service\CallApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpClient\CachingHttpClient;

class HomeController extends AbstractController
{
    private $apiService;

    public function __construct(CallApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    #[Route('/', name: 'app_home')]
    public function index(CacheInterface $cache): Response
    {
        $datasCache = $cache->get('datasCache', function (){
            return $this->apiService->getDofapiData();
        });

        return $this->render('home/home.html.twig', [
            "datas" => $datasCache
        ]);
    }
}
