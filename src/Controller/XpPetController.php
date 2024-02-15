<?php

namespace App\Controller;

use App\Entity\Resource;
use App\Form\XpPetType;
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
        $form = $this->createForm(XpPetType::class);
        $form->handleRequest($request);
        $search = $request->query->get('search');

        $profitability = null;

        $query = $resourceRepository->findResourcesWithXpPet($search);

        // Définir le nombre de résultats par page
        $limit = 10;

        // Récupérer le numéro de la page actuelle
        $page = $request->query->getInt('page', 1);

        // Paginer les résultats
        $pagination = $paginator->paginate(
            $query, // Requête ou queryBuilder
            $page,         // Numéro de la page actuelle
            $limit         // Nombre de résultats par page
        );

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Trouver la ressource sélectionnée
            $resource = $entityManager->getRepository(Resource::class)->find($data['resource']);

            // Calcul de la rentabilité
            $xpPet = $resource ? $resource->getXpPet() : 0;
            $price = $data['price'];
            $profitability = $price != 0 ? ($xpPet / $price) * 100 : 0; // Pourcentage de rentabilité
        }

        return $this->render('xp_pet/index.html.twig', [
            'form' => $form->createView(),
            'profitability' => $profitability,
            'pagination' => $pagination,
            'search' => $search
        ]);
    }
}
