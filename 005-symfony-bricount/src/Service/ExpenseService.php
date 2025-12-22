<?php

namespace App\Service;

use App\DTO\ExpenseDTO;
use App\Entity\Expense;
use App\Entity\User;
use App\Entity\Wallet;
use App\Repository\ExpenseRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class ExpenseService
{

    public function __construct(
        private readonly ExpenseRepository      $expenseRepository,
        private readonly EntityManagerInterface $entityManager,
    )
    {
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

    public function createExpense(Wallet $wallet, ExpenseDTO $dto, User $creator): Expense
    {
        $expense = new Expense();

        $expense->setUid(Uuid::v7()->toString());

        //convertir en integer par une multiplication par 100
        $amountInCents = (int)($dto->amount * 100);
        $expense->setAmount($amountInCents);
        //remplissage des autres champs
        $expense->setDescription($dto->description);
        $expense->setIcon($dto->icon); // j'enregistre juste la classe CSS pour les icones
        $expense->setWallet($wallet);
        $expense->setCreatedBy($creator);
        $expense->setCreatedDate(new DateTime());

        //calcul auto de la somme complète
        $currentTotal = $wallet->getTotalAmount();
        $wallet->setTotalAmount($currentTotal + $amountInCents);

        $this->entityManager->persist($expense);
        $this->entityManager->flush();

        return $expense;
    }

    public function deleteExpense(Expense $expense): void
    {
        //c'est ici que je fais le soft delete
        $expense->setIsDeleted(true);

        //Recalcul du total du Wallet
        $wallet = $expense->getWallet();
        $currentTotal = $wallet->getTotalAmount();
        $expenseAmount = $expense->getAmount();
        $wallet->setTotalAmount($currentTotal - $expenseAmount);

        $this->entityManager->flush();
    }
}

