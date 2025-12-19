<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Wallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findUsersNotInWallet(Wallet $wallet): array
    {
        return $this->createQueryBuilder('u')
            // première condition pour prendre juste les users valides
//            ->where('u.is_deleted = false')
            //deuxième condition pour prendre ceux qui ne sont pas le wallet
            ->andWhere('u.id NOT IN (
                SELECT IDENTITY(xuw.targetUser)
                FROM App\Entity\XUserWallet xuw
                WHERE xuw.wallet = :wallet
                AND xuw.isDeleted = false
            )')
            ->setParameter('wallet', $wallet)
            ->orderBy('u.email', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
