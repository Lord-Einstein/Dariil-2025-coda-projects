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

    /**
     * Récupère tous les liens pour un utilisateur, triés par date d'ajout récente.
     * @return XUserWallet[]
     */
    public function findLinksByUser(User $user): array
    {
        return $this->createQueryBuilder('xuw')
            // On joint le wallet pour récupérer ses infos (titre, montant)
            ->innerJoin('xuw.wallet', 'w')
            ->addSelect('w') // Optimisation : on charge le wallet tout de suite

            ->where('xuw.targetUser = :user')
            ->andWhere('xuw.isDeleted = false') // Lien actif
            ->andWhere('w.isDeleted = false')   // Wallet actif

            ->setParameter('user', $user)

            // C'EST ICI LE SECRET DU TRI :
            // On trie sur la date du LIEN (xuw), pas la date du wallet.
            // Si on t'ajoute aujourd'hui à un vieux wallet, il apparaitra en premier.
            ->orderBy('xuw.createdDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
