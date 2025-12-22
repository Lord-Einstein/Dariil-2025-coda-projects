<?php

namespace App\Controller\Wallets;

use App\Entity\User;
use App\Entity\Wallet;
use App\Service\WalletService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteController extends AbstractController
{
    #[Route('/wallets/{uid}/delete', name: 'wallets_delete', methods: ['GET'])]
    public function index(
        #[MapEntity(mapping: ['uid' => 'uid'])]
        Wallet        $wallet,
        WalletService $walletService
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $walletService->deleteWallet($wallet, $user);

        $this->addFlash('success', 'Le portefeuille a été supprimé.');

        return $this->redirectToRoute('wallets_list');
    }
}

