<?php

namespace App\Controller\Wallets;

use App\Entity\Wallet;
use App\Service\ExpenseService;
use App\Service\WalletService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class ShowController extends AbstractController
{
    #[Route('/wallets/{uid}', name: 'wallets_show', methods: ['GET'])]
    public function index(
        #[MapEntity(mapping: ['uid' => 'uid'])]
        Wallet                   $wallet,
        WalletService            $walletService,
        ExpenseService           $expenseService,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] int $limit = 10
    ): Response
    {

        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        //vérification du droit d'accès
        $access = $walletService->getUserAccessOnWallet($user, $wallet);

        if ($access === null) {
            $this->addFlash('danger', "Vous n'avez pas les droits d'accès à ce portefeuille.");
            return $this->redirectToRoute('wallets_list');
        }

        //1- Récupérer les depenses
        $expenses = $expenseService->getExpensesForWallet($wallet, $page, $limit);

        //2- Compter la pagin...
        $totalExpenses = $expenseService->getCountExpensesForWallet($wallet);
        $totalPages = (int)ceil($totalExpenses / $limit);

        return $this->render('wallets/show/index.html.twig', [
            'wallet' => $wallet,
            'expenses' => $expenses,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'limit' => $limit
        ]);
    }
}
