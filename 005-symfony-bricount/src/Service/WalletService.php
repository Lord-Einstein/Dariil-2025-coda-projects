<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Wallet;
use App\Entity\XUserWallet;
use App\Repository\WalletRepository;
use App\Repository\XUserWalletRepository;

class WalletService
{
    private readonly WalletRepository $walletRepository;
    private readonly XUserWalletRepository $xUserWalletRepository;

    public function __construct(WalletRepository $walletRepository, xUserWalletRepository $xUserWalletRepository)
    {
        $this->walletRepository = $walletRepository;
        $this->xUserWalletRepository = $xUserWalletRepository;
    }

    public function getWalletsForUser(User $user): array
    {
        return $this->walletRepository->findWalletForUser($user);
    }

    public function getUserAccessOnWallet(User $user, Wallet $wallet): null|XUserWallet
    {
        return $this->xUserWalletRepository->findOneBy([
            'targetUser' => $user,
            'wallet' => $wallet
        ]);
    }

}
