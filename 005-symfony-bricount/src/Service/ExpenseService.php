<?php

namespace App\Service;

use App\Entity\Wallet;
use App\Repository\ExpenseRepository;

class ExpenseService
{
    private readonly ExpenseRepository $expenseRepository;

    public function __construct(ExpenseRepository $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    //Recupère les dépenses par page
    public function getExpensesForWallet(Wallet $wallet, int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;
        return $this->expenseRepository->findExpensesForWallet($wallet, $offset, $limit);
    }

    //Conte le total
    public function getCountExpensesForWallet(Wallet $wallet): int
    {
        return $this->expenseRepository->countExpensesForWallet($wallet);
    }
}

