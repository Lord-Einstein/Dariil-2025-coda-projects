<?php

namespace App\Repository;

use App\Entity\Expense;
use App\Entity\Wallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Expense>
 */
class ExpenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Expense::class);
    }

    public function findExpensesForWallet(Wallet $wallet, int $page, int $limit): array
    {

        return $this->createQueryBuilder('e')
            ->innerJoin('e.wallet', 'w')
            ->where('e.wallet = :wallet')
            ->andWhere('e.isDeleted = false')
            ->andWhere('w.isDeleted = false')
            ->setParameter('wallet', $wallet)
            ->orderBy('e.createdDate', 'DESC')
            ->setFirstResult($page)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function countExpensesForWallet(Wallet $wallet): int
    {
        return $this->createQueryBuilder('expense')
            ->select('COUNT(expense.id)')
            ->andWhere('expense.wallet = :wallet')
            ->setParameter('wallet', $wallet)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findExpensesSinceLastSettlement(Wallet $wallet): array
    {
        $qb = $this->createQueryBuilder('e')
            ->where('e.wallet = :wallet')
            ->andWhere('e.isDeleted = false')
            ->setParameter('wallet', $wallet);

        if ($wallet->getLastSettlementDate()) {
            $qb->andWhere('e.createdDate > :lastSettlement')
                ->setParameter('lastSettlement', $wallet->getLastSettlementDate());
        }

        return $qb->getQuery()->getResult();
    }

}
