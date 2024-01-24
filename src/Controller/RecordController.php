<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecordController extends AbstractController
{
    #[Route('/record', name: 'app_record')]
    public function index(): Response
    {


        return $this->render('record/index.html.twig', [
            'controller_name' => 'RecordController',
        ]);
    }
}
