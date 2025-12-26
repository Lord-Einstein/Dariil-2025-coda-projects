<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Wallet;
use App\Entity\XUserWallet;
use App\Repository\XUserWalletRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

readonly class XUserWalletService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private XUserWalletRepository  $xUserWalletRepository
    )
    {
    } //je procède par promotion de... propriété.

    public function create(Wallet $wallet, User $targetUser, string $role, User $initiator): XUserWallet
    {
        // vérifier si l'association existe déjà
        $association = $this->xUserWalletRepository->findOneBy([
            'wallet' => $wallet,
            'targetUser' => $targetUser
        ]);
        // if not exist.. je la crée en remplissant les champs obligatoires
        if (!$association) {
            $association = new XUserWallet();
            $association->setWallet($wallet);
            $association->setTargetUser($targetUser);

            $association->setCreatedBy($initiator);
            $association->setCreatedDate(new DateTime());
        } else {
            $association->setUpdatedDate(new DateTime());
            $association->setUpdatedBy($initiator);
        }

        // mettre à jour le rôle
        $association->setRole($role);
        //à chaque fois qu'on passe par cette fonction, je m'assure que le lien qui en sort est valide.
        $association->setIsDeleted(false);


        $this->entityManager->persist($association);
        $this->entityManager->flush();

        return $association;
    }
}




