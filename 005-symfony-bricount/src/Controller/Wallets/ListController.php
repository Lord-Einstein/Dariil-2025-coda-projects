<?php

namespace App\Controller\Wallets;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ListController extends AbstractController
{
    #[Route('/wallets', name: 'wallets_list', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('wallets/list/index.html.twig', [
            'controller_name' => 'ListController',
        ]);
    }
}
