<?php

namespace App\Controller\Expense;

use App\Entity\Expense;
use App\Entity\User;
use App\Entity\Wallet;
use App\Service\ExpenseService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteController extends AbstractController
{
    #[Route('/wallets/{wallet_uid}/expense/{expense_uid}/delete', name: 'wallets_expense_delete', methods: ['GET'])]
    public function index(
        //je mappe {wallet_uid} de l'URL vers la propriété 'uid' de l'entité Wallet
        #[MapEntity(mapping: ['wallet_uid' => 'uid'])]
        Wallet         $wallet,
        // et ensuite, faut mapper {expense_uid} de l'URL vers la propriété 'uid' de l'entité Expense
        #[MapEntity(mapping: ['expense_uid' => 'uid'])]
        Expense        $expense,

        ExpenseService $expenseService
    ): Response
    {
        if ($expense->getWallet() !== $wallet) { //la dépense doit appartenir au wallet courant...
            throw $this->createNotFoundException("Cette dépense n'appartient pas à ce portefeuille.");
        }

        /** @var User $user */
        $user = $this->getUser();

        // Appel au service
        $expenseService->deleteExpense($expense, $user);

        $this->addFlash('success', 'La dépense a été supprimée.');

        // Retour au détail du wallet
        return $this->redirectToRoute('wallets_show', ['uid' => $wallet->getUid()]);
    }
}

