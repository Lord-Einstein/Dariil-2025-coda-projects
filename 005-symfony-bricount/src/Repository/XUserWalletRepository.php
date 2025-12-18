<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Wallet;
use App\Entity\XUserWallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<XUserWallet>
 */
class XUserWalletRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, XUserWallet::class);
    }

    /**
     * Trouve un lien d'accès VALIDE (non supprimé, sur un wallet non supprimé)
     */
    public function findActiveLink(User $user, Wallet $wallet): ?XUserWallet
    {
        return $this->createQueryBuilder('xuw')
            // Jointure pour vérifier l'état du wallet
            ->innerJoin('xuw.wallet', 'w')

            // Conditions de join
            ->where('xuw.targetUser = :user')
            ->andWhere('xuw.wallet = :wallet')
            ->andWhere('xuw.isDeleted = false') // checker si le lien est actif
            ->andWhere('w.isDeleted = false')   // puis si le portefeuille est actif

            ->setParameter('user', $user)
            ->setParameter('wallet', $wallet)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
