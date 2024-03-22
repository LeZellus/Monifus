<?php

namespace App\Controller;

use App\Entity\Guild;
use App\Form\GuildType;
use App\Repository\GuildRepository;
use App\Service\BreadcrumbService;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/guildes')]
class GuildController extends AbstractController
{
    private BreadcrumbService $breadcrumbService;
    private Security $security;
    private EntityManagerInterface $entityManager;
    private GuildRepository $guildRepository;

    public function __construct(BreadcrumbService $breadcrumbService, Security$security, EntityManagerInterface $entityManager, GuildRepository $guildRepository)
    {
        $this->breadcrumbService = $breadcrumbService;
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->guildRepository = $guildRepository;
    }

    #[Route('/', name: 'app_guilds')]
    public function index(GuildRepository $guildRepository): Response
    {
        $guilds = $guildRepository->findAll();
        $userGuild = null;
        $user = $this->security->getUser();

        if ($user) {
            $userGuild = $user->getGuild();
        }

        $this->breadcrumbService->setBreadcrumbs("Guildes", "");

        return $this->render('guild/index.html.twig', [
            'guilds' => $guilds,
            'userGuild' => $userGuild,
            'isUserLoggedIn' => (bool)$user
        ]);
    }

    #[Route('/nouvelle', name: 'app_guilds_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $guild = new Guild();
        $form = $this->createForm(GuildType::class, $guild);
        $form->handleRequest($request);

        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {

            if($user->getGuild()) {
                $this->addFlash('info', 'Vous êtes déjà membre d\'une guilde.');

                return $this->render('guild/new.html.twig', [
                    'form' => $form,
                ]);
            }

            $guild->setLeader($user);
            $user->setGuild($guild);
            $entityManager->persist($guild);
            $entityManager->persist($user);
            $entityManager->flush();

            // Rediriger vers la liste des Monitors
            return $this->redirectToRoute('app_guilds');
        }

        $this->breadcrumbService->setBreadcrumbs("Guilde", "/guildes");
        $this->breadcrumbService->setBreadcrumbs("Nouvelle", "");

        return $this->render('guild/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{name}', name: 'app_guilds_show', methods: ['GET', 'POST'])]
    public function show(string $name, GuildRepository $guildRepository): Response
    {
        $name = str_replace('_', ' ', $name);
        $guild = $guildRepository->findOneBy(['name' => $name]);
        $user = $this->getUser();
        $isLeader = false;

        if($user) {
            $isLeader = $guildRepository->isUserLeaderOfGuild($user->getId());
        }

        if (!$guild) {
            throw new NotFoundHttpException('Aucune guilde trouvée');
        }

        return $this->render('guild/show.html.twig', [
            'guild' => $guild,
            'isLeader' => $isLeader
        ]);
    }

    #[Route('/editer/{name}', name: 'app_guilds_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, string $name, GuildRepository $guildRepository): Response
    {
        $name = str_replace('_', ' ', $name);
        $guild = $guildRepository->findOneBy(['name' => $name]);

        $form = $this->createForm(GuildType::class, $guild);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('app_guilds', [], Response::HTTP_SEE_OTHER);
        }

        $this->breadcrumbService->setBreadcrumbs("Guildes", '/guilds');
        $this->breadcrumbService->setBreadcrumbs("Editer", '');

        return $this->render('guild/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/rejoindre/{name}', name: 'app_guilds_join', methods: ['GET', 'POST'])]
    public function join(string $name): Response
    {
        return $this->handleGuildMembership($name, true);
    }

    #[Route('/partir/{name}', name: 'app_guilds_leave', methods: ['GET', 'POST'])]
    public function leave(string $name): Response
    {
        $name = str_replace('_', ' ', $name);
        $guild = $this->getGuild($name);
        $user = $this->getUser();

        // Vérifier si l'utilisateur appartient à la guilde
        if ($guild != $user->getGuild()) {
            $this->addFlash('error', 'Vous ne pouvez pas quitter une guilde à laquelle vous n\'appartenez pas.');
            return $this->redirectToRoute('app_guilds'); // Rediriger vers la liste des guildes
        }

        // Vérifier si l'utilisateur est le leader de la guilde
        if ($guild->getLeader() === $user) {
            $this->addFlash('error', 'Le créateur de la guilde ne peut pas quitter sa guilde.');
            return $this->redirectToRoute('app_guilds');
        }

        // Procéder au retrait de la guilde
        return $this->handleGuildMembership($name, false);
    }

    private function handleGuildMembership(string $name, bool $join): Response
    {
        $name = str_replace('_', ' ', $name);
        $guild = $this->getGuild($name);
        $user = $this->getUser();

        $user->setGuild($join ? $guild : null);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->redirectToRoute($join ? 'app_guilds_show' : 'app_guilds', ['name' => $name]);
    }

    private function getGuild(string $name): Guild
    {
        $guild = $this->guildRepository->findOneBy(['name' => $name]);

        if (!$guild) {
            throw new NotFoundHttpException('Aucune guilde trouvée');
        }

        return $guild;
    }
}
