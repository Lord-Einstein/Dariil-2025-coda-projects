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
        return $this->createQueryBuilder('expense')
            ->andWhere('expense.isDeleted = false')
            ->andWhere('expense.wallet = :wallet')
            ->setParameter('wallet', $wallet)
            ->orderBy('expense.createdDate', 'DESC')
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

    //    /**
    //     * @return Expense[] Returns an array of Expense objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Expense
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
