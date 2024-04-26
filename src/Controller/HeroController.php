<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\BreadcrumbService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HeroController extends AbstractController
{
    private BreadcrumbService $breadcrumbService;
    private UserRepository $userRepository;
    private ParameterBagInterface $params;

    public function __construct(BreadcrumbService $breadcrumbService, UserRepository $userRepository, ParameterBagInterface $params)
    {
        $this->breadcrumbService = $breadcrumbService;
        $this->userRepository = $userRepository;
        $this->params = $params;
    }

    #[Route('/heros', name: 'app_hero')]
    public function index(): Response
    {
        // Read environment-specific IDs
        $donorsIds = $this->getParameter('DONORS_IDS');
        $bugReportersIds = explode(',', $this->params->get('BUG_REPORTERS_IDS'));
        $suggestersIds = explode(',', $this->params->get('SUGGESTERS_IDS'));


        // Retrieve user information for each group
        $donors = $this->userRepository->findBy(['id' => $donorsIds]);
        $bugReporters = $this->userRepository->findBy(['id' => $bugReportersIds]);
        $suggesters = $this->userRepository->findBy(['id' => $suggestersIds]);

        // Pass data to the template
        return $this->render('hero/index.html.twig', [
            'donors' => $donors,
            'bugReporters' => $bugReporters,
            'suggesters' => $suggesters,
        ]);
    }
}
