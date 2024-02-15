<?php

namespace App\Controller;

use App\Entity\Resource;
use App\Form\XpPetPercentType;
use App\Repository\ResourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class XpPetController extends AbstractController
{
    #[Route('/xpetifus', name: 'app_xp_pet')]
    public function index(Request $request, EntityManagerInterface $entityManager, ResourceRepository $resourceRepository, PaginatorInterface $paginator): Response
    {
        $formXpPetPercent = $this->createForm(XpPetPercentType::class);
        $formXpPetPercent->handleRequest($request);

        $formXpPetNumber = $this->createForm(XpPetPercentType::class);
        $formXpPetNumber->handleRequest($request);

        $search = $request->query->get('search');
        $profitability = null;
        $number = null;

        $query = $resourceRepository->findResourcesWithXpPet($search);

        $limit = 10;
        $page = $request->query->getInt('page', 1);

        $pagination = $paginator->paginate(
            $query,
            $page,
            $limit
        );

        if ($formXpPetPercent->isSubmitted() && $formXpPetPercent->isValid()) {
            $data = $formXpPetPercent->getData();

            // Trouver la ressource sélectionnée
            $resource = $entityManager->getRepository(Resource::class)->find($data['resource']);

            // Calcul de la rentabilité
            $xpPet = $resource ? $resource->getXpPet() : 0;
            $price = $data['price'];
            $profitability = $price != 0 ? ($xpPet / $price) * 100 : 0; // Pourcentage de rentabilité
        }

        if ($formXpPetNumber->isSubmitted() && $formXpPetNumber->isValid()) {
            $data = $formXpPetNumber->getData();

            // Trouver la ressource sélectionnée
            $resource = $entityManager->getRepository(Resource::class)->find($data['resource']);

            // Calcul de la rentabilité
            $xpPet = $resource ? $resource->getXpPet() : 0;
            $price = $data['price'];
            $number = $price != 0 ? ($price / $xpPet) : 0;
        }

        return $this->render('xp_pet/index.html.twig', [
            'formXpPetPercent' => $formXpPetPercent->createView(),
            'formXpPetNumber' => $formXpPetNumber->createView(),
            'number' => $number,
            'profitability' => $profitability,
            'pagination' => $pagination,
            'search' => $search
        ]);
    }
}
