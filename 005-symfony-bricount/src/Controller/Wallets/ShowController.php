<?php

namespace App\Controller\Wallets;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ShowController extends AbstractController
{
    #[Route('/wallets/{id}', name: 'wallets_show', methods: ['GET'])]
    public function index(
        string $id
    ): Response
    {
        return $this->render('wallets/show/index.html.twig', [
            'controller_name' => 'ShowController',
            'id' => $id
        ]);
    }
}
