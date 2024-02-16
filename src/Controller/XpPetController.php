<?php

namespace App\Controller;

use App\Entity\Resource;
use App\Form\XpPetPercentType;
use App\Repository\ResourceRepository;
use App\Service\BreadcrumbService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class XpPetController extends AbstractController
{
    private BreadcrumbService $breadcrumbService;

    public function __construct(BreadcrumbService $breadcrumbService)
    {
        $this->breadcrumbService = $breadcrumbService;
    }

    #[Route('/xpetifus', name: 'app_xp_pet')]
    public function index(Request $request, EntityManagerInterface $entityManager, ResourceRepository $resourceRepository, PaginatorInterface $paginator): Response
    {
        $formXpPetPercent = $this->createForm(XpPetPercentType::class);
        $formXpPetPercent->handleRequest($request);

        $formXpPetNumber = $this->createForm(XpPetPercentType::class);
        $formXpPetNumber->handleRequest($request);

        $search = $request->query->get('search');

        $query = $resourceRepository->findResourcesWithXpPet($search);
        $limit = 10;
        $page = $request->query->getInt('page', 1);

        $pagination = $paginator->paginate($query, $page, $limit);

        $profitability = $this->calculateProfitability($formXpPetPercent, $entityManager);
        $number = $this->calculateNumber($formXpPetNumber, $entityManager);

        $this->breadcrumbService->setBreadcrumbs("XPETifus", "");

        return $this->render('xp_pet/index.html.twig', [
            'formXpPetPercent' => $formXpPetPercent->createView(),
            'formXpPetNumber' => $formXpPetNumber->createView(),
            'number' => $number,
            'profitability' => $profitability,
            'pagination' => $pagination,
            'search' => $search
        ]);
    }

    private function calculateProfitability($form, $entityManager): float|int|null
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $resource = $entityManager->getRepository(Resource::class)->find($data['resource']);
            $xpPet = $resource ? $resource->getXpPet() : 0;
            $price = $data['price'];

            return $price != 0 ? ($xpPet / $price) * 100 : null;
        }

        return null;
    }

    private function calculateNumber($form, $entityManager): float|int|null
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $resource = $entityManager->getRepository(Resource::class)->find($data['resource']);
            $xpPet = $resource ? $resource->getXpPet() : 0;
            $price = $data['price'];

            return $xpPet != 0 ? ($price / $xpPet) : null;
        }

        return null;
    }
}
