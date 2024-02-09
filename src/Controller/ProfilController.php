<?php

namespace App\Controller;

use App\Form\ProfilType;
use App\Service\BreadcrumbService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    private BreadcrumbService $breadcrumbService;

    public function __construct(BreadcrumbService $breadcrumbService)
    {
        $this->breadcrumbService = $breadcrumbService;
    }
    #[Route('/profil', name: 'app_profil')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();
        }

        $this->breadcrumbService->setBreadcrumbs("Mon profil", "");

        return $this->render('profil/index.html.twig',[
            'editForm' => $form->createView(),
        ]);
    }
}

