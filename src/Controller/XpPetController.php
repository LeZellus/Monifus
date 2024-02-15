<?php

namespace App\Controller;

use App\Entity\Resource;
use App\Form\XpPetType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class XpPetController extends AbstractController
{
    #[Route('/xpetifus', name: 'app_xp_pet')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(XpPetType::class);
        $form->handleRequest($request);

        $profitability = null;

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
            'profitability' => $profitability
        ]);
    }
}
