<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Wallet;
use App\Entity\XUserWallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Wallet>
 */
class WalletRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wallet::class);
    }

    /**
     * @param User $user
     * @return Wallet[] Return a array of Wallet objects with filtered on one user.
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

    //    /**
    //     * @return Wallet[] Returns an array of Wallet objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('w.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Wallet
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
