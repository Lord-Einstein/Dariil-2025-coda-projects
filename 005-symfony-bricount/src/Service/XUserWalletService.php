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

    public function create(Wallet $wallet, User $user, string $role): XUserWallet
    {
        // vérifier si l'association existe déjà
        $association = $this->xUserWalletRepository->findOneBy([
            'wallet' => $wallet,
            'targetUser' => $user
        ]);
        // if not exist.. je la crée en remplissant les champs obligatoires
        if (!$association) {
            $association = new XUserWallet();
            $association->setWallet($wallet);
            $association->setTargetUser($user);
            $association->setCreatedBy($user);
            $association->setCreatedDate(new DateTime());
        }

        // mettre à jour le rôle
        $association->setRole($role);

        $this->entityManager->persist($association);
        //je vais flush directement dans le WalletService en bloc...

        return $association;
    }
}




