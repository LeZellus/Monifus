<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\RecordRepository;
use App\Service\BreadcrumbService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ExtractYoutubeUrlService;

class HomeController extends AbstractController
{
    private BreadcrumbService $breadcrumbService;
    private RecordRepository $recordRepository;
    private ExtractYoutubeUrlService $extractYoutubeUrlService;

    public function __construct(BreadcrumbService $breadcrumbService, ExtractYoutubeUrlService $extractYoutubeUrlService)
    {
        $this->breadcrumbService = $breadcrumbService;
        $this->extractYoutubeUrlService = $extractYoutubeUrlService;
    }

    #[Route('/', name: 'app_home')]
    public function index(RecordRepository $recordRepository): Response
    {
        $lastRecord = $recordRepository->findLatestRecord();
        $youtubeUrl = $lastRecord->getVideoLink();
        $youtubeId = $this->extractYoutubeUrlService->extractYouTubeID($youtubeUrl);

        return $this->render('home/index.html.twig', [
            'record' => $lastRecord,
            'youtubeId' => $youtubeId
        ]);
    }

    #[Route("/api/user/count", name: "api_user_count")]
    public function userCount(UserRepository $userRepository): JsonResponse
    {
        $userCount = $userRepository->count([]);

        return new JsonResponse(['count' => $userCount]);
    }
}
