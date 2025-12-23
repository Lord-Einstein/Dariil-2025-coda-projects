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


    // Compte les wallets créés par l'utilisateur et non supprimés
    public function countOwnedWallets(User $user): int
    {
        return $this->createQueryBuilder('w')
            ->select('count(w.id)')
            ->where('w.createdBy = :user') // On utilise createdBy de BaseEntity
            ->andWhere('w.isDeleted = false')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    // Compte les wallets rejoints (où je suis invité via XUserWallet)
    public function countJoinedWallets(User $user): int
    {
        return $this->createQueryBuilder('w')
            ->select('count(w.id)')
            ->innerJoin('w.xUserWallets', 'xuw') // Relation dans Wallet.php
            ->where('xuw.targetUser = :user')    // Relation dans XUserWallet.php
            ->andWhere('w.createdBy != :user')   // Je ne suis pas le créateur
            ->andWhere('w.isDeleted = :deleted')
            ->andWhere('xuw.isDeleted = :deleted') // Le lien utilisateur n'est pas supprimé
            ->setParameter('user', $user)
            ->setParameter('deleted', false)
            ->getQuery()
            ->getSingleScalarResult();
    }

    // Compte les connexions uniques (Personnes croisées dans mes wallets)
    public function countUniqueConnections(User $user): int
    {
        // Stratégie : On cherche tous les XUserWallet (les participants)
        // qui appartiennent à des Wallets où JE suis aussi (soit créateur, soit participant)

        $qb = $this->createQueryBuilder('w');

        return $qb->select('count(DISTINCT xuw_other.targetUser)')
            ->innerJoin('w.xUserWallets', 'xuw_other') // Les autres participants
            ->leftJoin('w.xUserWallets', 'xuw_me')     // Moi en tant que participant (peut être null si je suis owner)

            // Condition : Le wallet est actif
            ->where('w.isDeleted = :deleted')

            // Condition : JE suis dans ce wallet (soit créateur, soit via XUserWallet)
            ->andWhere($qb->expr()->orX(
                'w.createdBy = :user',
                'xuw_me.targetUser = :user'
            ))

            // Condition : La personne comptée n'est pas MOI
            ->andWhere('xuw_other.targetUser != :user')
            ->andWhere('xuw_other.isDeleted = :deleted')
            ->setParameter('user', $user)
            ->setParameter('deleted', false)
            ->getQuery()
            ->getSingleScalarResult();
    }

}
