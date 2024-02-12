<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('oauth/discord', name:'oauth_discord_')]
final class DiscordController
{
    #[Route('/start', name: 'start')]
    public function start (ClientRegistry $clientRegistry): RedirectResponse
    {
        $options = [
            'scope' => ['identify', 'email'],
            'prompt' => 'none'
        ];

        return $clientRegistry->getClient("discord")->redirect($options['scope'], $options);
    }

    #[Route('/login', name: 'login')]
    public function login(Request $request, ClientRegistry $clientRegistry): Response
    {
    }
}
