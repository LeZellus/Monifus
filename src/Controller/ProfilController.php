<?php

namespace App\Controller;

use App\Form\PersonalDataType;
use App\Form\ProfilType;
use App\Form\SocialUserType;
use App\Repository\UserRepository;
use App\Service\BreadcrumbService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfilController extends AbstractController
{
    private BreadcrumbService $breadcrumbService;
    private Security $security;

    public function __construct(BreadcrumbService $breadcrumbService, Security $security)
    {
        $this->breadcrumbService = $breadcrumbService;
        $this->security = $security;
    }
    #[Route('/profil/mon-profil', name: 'app_profil')]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $user = $this->security->getUser();
        $em = $doctrine->getManager();

        // Créer les formulaires
        $form = $this->createForm(ProfilType::class, $user);
        $personalDataForm = $this->createForm(PersonalDataType::class, $user);
        $socialDataForm = $this->createForm(SocialUserType::class, $user);

        // Cloner la requête pour chaque formulaire
        $formRequest = clone $request;
        $personalDataRequest = clone $request;
        $socialDataRequest = clone $request;

        // Traiter le premier formulaire
        $form->handleRequest($formRequest);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
        }

        // Traiter le deuxième formulaire
        $personalDataForm->handleRequest($personalDataRequest);
        if ($personalDataForm->isSubmitted() && $personalDataForm->isValid()) {
            $em->persist($user);
            $em->flush();
        }

        $socialDataForm->handleRequest($socialDataRequest);
        if ($socialDataForm->isSubmitted() && $socialDataForm->isValid()) {
            $em->persist($user);
            $em->flush();
        }

        // Définir le fil d'Ariane et rendre la vue
        $this->breadcrumbService->setBreadcrumbs("Mon profil", "");

        return $this->render('profil/index.html.twig', [
            'editForm' => $form->createView(),
            'personalDataForm' => $personalDataForm->createView(),
            'socialDataForm' => $socialDataForm->createView()
        ]);
    }

    #[Route('/profil/{id}', name: 'app_profil_show')]
    public function publicProfile(int $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        return $this->render('profil/show.html.twig', [
            'user' => $user,
        ]);
    }
}

