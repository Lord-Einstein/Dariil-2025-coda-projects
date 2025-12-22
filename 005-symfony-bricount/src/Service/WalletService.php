<?php

namespace App\Service;

use App\DTO\WalletDTO;
use App\Entity\User;
use App\Entity\Wallet;
use App\Entity\XUserWallet;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use App\Repository\XUserWalletRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class WalletService
{


    public function __construct(
        private readonly WalletRepository       $walletRepository,
        private readonly xUserWalletRepository  $xUserWalletRepository,
        //ajouter le entity manager et faire l'injection de xUserWalletServce.
        private readonly EntityManagerInterface $entityManager,
        private readonly XUserWalletService     $xUserWalletService,
        private readonly UserRepository         $userRepository
    )
    {
    }

    public function getWalletsForUser(User $user): array
    {
        return $this->walletRepository->findWalletForUser($user);
    }

    public function getUserAccessOnWallet(User $user, Wallet $wallet): null|XUserWallet
    {
        return $this->xUserWalletRepository->findActiveLink($user, $wallet);
    }

    public function createWallet(WalletDTO $dto, User $owner): Wallet
    {
        //créer un wallet
        $wallet = new Wallet();
        $wallet->setUid(Uuid::v7()->toString());
        $wallet->setLabel($dto->name); //récupérer le nom depuis le DTO...
        $wallet->setTotalAmount(0);
        $wallet->setCreatedDate(new DateTime());

        $wallet->setCreatedBy($owner);

        $this->entityManager->persist($wallet);

        //implémenter le lien avec le caractère d'admin directement pour le créateur courant
        $this->xUserWalletService->create($wallet, $owner, 'admin', $owner);


        $this->entityManager->flush();

        return $wallet;
    }

    public function updateWallet(Wallet $wallet, WalletDTO $dto, User $updater): Wallet
    {
        $wallet->setLabel($dto->name);

        $wallet->setUpdatedDate(new DateTime());
        $wallet->setUpdatedBy($updater);

        $this->entityManager->persist($wallet);
        $this->entityManager->flush();

        return $wallet;
    }

    public function findAvailableUsersForWallet(Wallet $wallet): array
    {
        return $this->userRepository->findUsersNotInWallet($wallet);
    }

    public function getWalletsAccessForUser(User $user): array
    {
        return $this->xUserWalletRepository->findLinksByUser($user);
    }

    public function deleteWallet(Wallet $wallet): void
    {
        $wallet->setIsDeleted(true);
        // revoir ensuite l'option de "supprimer" en soft toutes les dépenses associées
        // ou marquer les liens xUserWallet comme deleted.
        $this->entityManager->flush();
    }

}

