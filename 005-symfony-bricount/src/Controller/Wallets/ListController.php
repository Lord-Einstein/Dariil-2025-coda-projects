<?php

namespace App\Controller\Wallets;

use App\Entity\User;
use App\Service\WalletService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ListController extends AbstractController
{
    #[Route('/wallets', name: 'wallets_list', methods: ['GET'])]
    public function index(WalletService $walletService): Response
    {
        //Prendre l'utilisateur connecté.
        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $walletLinks = $walletService->getWalletsAccessForUser($user);


        return $this->render('wallets/list/index.html.twig', [
            'walletLinks' => $walletLinks,
        ]);
    }
}
