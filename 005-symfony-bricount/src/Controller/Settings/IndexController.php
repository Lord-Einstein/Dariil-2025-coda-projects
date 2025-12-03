<?php

namespace App\Controller\Settings;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/settings', name: 'settings', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('settings/index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
