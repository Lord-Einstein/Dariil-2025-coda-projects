<?php

namespace App\Controller\Wallets;

use App\Entity\User;
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
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($wallet->isDeleted()) {
            throw $this->createNotFoundException('Ce portefeuille a été supprimé.');
        }

        //vérification du droit d'accès
        $access = $walletService->getUserAccessOnWallet($user, $wallet);

        if ($access === null) {
            $this->addFlash('danger', "Ce portefeuille est inacessible.");
            return $this->redirectToRoute('wallets_list');
        }

        //1- Récupérer les dépenses
        $expenses = $expenseService->getExpensesForWallet($wallet, $page, $limit);

        //2- Compter la pagination...
        $totalExpenses = $expenseService->getCountExpensesForWallet($wallet);
        $totalPages = (int)ceil($totalExpenses / $limit);

        $balances = $walletService->getUserBalances($wallet);

        $members = $wallet->getMembers();


        return $this->render('wallets/show/index.html.twig', [
            'wallet' => $wallet,
            'expenses' => $expenses,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'limit' => $limit,
            //je veux passer le droit d'accès à ma vue twig pour pas afficher le bouton au non admin
            'userAccess' => $access,
            // On passe les nouvelles variables à la vue
            'balances' => $balances,
            'members' => $members
        ]);
    }

    /**
     * Nouvelle méthode pour gérer l'action "Marquer comme réglé"
     */
    #[Route('/wallets/{uid}/settle', name: 'wallets_settle', methods: ['POST'])]
    public function settle(
        #[MapEntity(mapping: ['uid' => 'uid'])]
        Wallet        $wallet,
        WalletService $walletService
    ): Response
    {

        /** @var User $user */
        $user = $this->getUser();
        $access = $walletService->getUserAccessOnWallet($user, $wallet);

        if (!$access || $access->getRole() !== 'admin') {
            $this->addFlash('danger', 'Seul un administrateur peut solder les comptes.');
            return $this->redirectToRoute('wallets_show', ['uid' => $wallet->getUid()]);
        }

        // Action
        $walletService->markAsSettled($wallet);

        //Feedback et Redirection
        $this->addFlash('success', 'Les comptes ont été marqués comme réglés.');

        return $this->redirectToRoute('wallets_show', ['uid' => $wallet->getUid()]);
    }

}
