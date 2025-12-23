<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Wallet;
use App\Entity\XUserWallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class WalletRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wallet::class);
    }

    /**
     * @param User $user
     * @return Wallet[] Return array of Wallet objects with filtered on one user.
     */
    public function findWalletForUser(User $user): array
    {
        return $this->createQueryBuilder('wallet')
            ->innerJoin(XUserWallet::class, 'xuser_wallet', 'WITH', 'xuser_wallet.wallet = wallet.id AND xuser_wallet.isDeleted = false AND xuser_wallet.targetUser = :user')
            ->andWhere('wallet.isDeleted = false')
            ->setParameter('user', $user)
            ->orderBy('wallet.createdDate', 'DESC')
            ->getQuery()
            ->getResult();

    }

    public function calculateTotalBalance(Wallet $wallet): int
    {
        return (int)$this->createQueryBuilder('w')
            ->select('SUM(e.amount)')
            ->join('w.expenses', 'e')
            ->where('w.id = :id')
            ->andWhere('e.isDeleted = false')
            ->setParameter('id', $wallet->getId())
            ->getQuery()
            ->getSingleScalarResult();
    }

}
