<?php

namespace App\Repository;

use App\Entity\Expense;
use App\Entity\User;
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

    public function getTotalSpentByUser(User $user): float
    {
        $result = $this->createQueryBuilder('e')
            ->select('SUM(e.amount)')
            ->innerJoin('e.wallet', 'w')
            ->where('e.createdBy = :user')
            ->andWhere('w.isDeleted = :deleted') // Le wallet doit exister
            ->andWhere('e.isDeleted = :deleted') // La dépense doit exister.
            ->setParameter('user', $user)
            ->setParameter('deleted', false)
            ->getQuery()
            ->getSingleScalarResult();

        return $result ? (float)$result : 0.0;
        //On verra bien mais, je pense que faudra venir mettre un petit /100 ou pas ?
        //J'espère vraiment me souvenir de revenir pour effacer ces comms :p. MDR je délire là...
    }

    // toutes les dépenses actives d'un user dans un wallet spécifique
    public function findActiveExpensesByWalletAndUser(Wallet $wallet, User $user): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.wallet = :wallet')
            ->andWhere('e.createdBy = :user')
            ->andWhere('e.isDeleted = :deleted')
            ->setParameter('wallet', $wallet)
            ->setParameter('user', $user)
            ->setParameter('deleted', false)
            ->getQuery()
            ->getResult();
    }

}
