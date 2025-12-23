<?php

namespace App\Service;

use App\DTO\WalletDTO;
use App\Entity\User;
use App\Entity\Wallet;
use App\Entity\XUserWallet;
use App\Repository\ExpenseRepository;
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
        private readonly UserRepository         $userRepository,
        private readonly ExpenseRepository      $expenseRepository
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

    public function deleteWallet(Wallet $wallet, User $owner): void
    {
        $wallet->setIsDeleted(true);
        $wallet->setDeletedBy($owner);
        $wallet->setDeletedDate(new DateTime());


        // revoir ensuite l'option de "supprimer" en soft toutes les dépenses associées
        // ou marquer les liens xUserWallet comme deleted.
        $this->entityManager->flush();
    }

    //remplacer par refresh à supprimer at the end...
    public function updateTotalBalance(Wallet $wallet): void
    {

        $total = $this->walletRepository->calculateTotalBalance($wallet);
        $wallet->setTotalAmount($total);

        $this->entityManager->flush();
    }

    public function markAsSettled(Wallet $wallet): void
    {
        $wallet->setLastSettlementDate(new DateTime());

        $this->entityManager->flush();
    }

    public function getUserBalances(Wallet $wallet): array
    {
        // 1. On récupère tout le monde via notre nouvelle méthode (plus de if owner séparé)
        $members = $wallet->getMembers();
        if (empty($members)) {
            return [];
        }

        // 2. Calcul des totaux dépensés par personne
        // On initialise à 0 pour tout le monde
        $spendingByUser = [];
        foreach ($members as $userId => $user) {
            $spendingByUser[$userId] = 0.0;
        }

        // On additionne les dépenses
        $expenses = $this->expenseRepository->findExpensesSinceLastSettlement($wallet);
        $totalAmount = 0.0;

        foreach ($expenses as $expense) {
            // Sécurité : si le créateur n'est plus dans la liste (ex: supprimé), on ignore ou on gère
            $userId = $expense->getCreatedBy()->getId();
            if (isset($spendingByUser[$userId])) {
                $amount = (float)$expense->getAmount();
                $spendingByUser[$userId] += $amount;
                $totalAmount += $amount;
            }
        }

        // 3. Calcul de la moyenne (Part équitable)
        $countUsers = count($spendingByUser);
        if ($countUsers === 0) {
            return [];
        }
        $averageShare = $totalAmount / $countUsers;

        // 4. Séparation Débiteurs / Créanciers
        $debtors = [];
        $creditors = [];


        foreach ($spendingByUser as $userId => $amount) {
            $balance = round(($amount - $averageShare), 2, PHP_ROUND_HALF_UP);
            if ($balance < -0.01) {
                $debtors[$userId] = abs($balance);
            } elseif ($balance > 0.01) {
                $creditors[$userId] = $balance;
            }
        }

        // 5. Déléguer la résolution complexe à une méthode privée
        return $this->resolveTransfers($debtors, $creditors);
    }

    /**
     * Algorithme de résolution des dettes (Méthode extraite pour réduire la complexité)
     */
    private function resolveTransfers(array $debtors, array $creditors): array
    {
        $transfers = [];
        arsort($debtors);
        arsort($creditors);

        foreach ($debtors as $debtorId => $debtAmount) {
            foreach ($creditors as $creditorId => $creditAmount) {
                if ($debtAmount <= 0.001) {
                    break;
                }
                if ($creditAmount <= 0.001) {
                    continue;
                }

                $amountToTransfer = min($debtAmount, $creditAmount);

                $transfers[$debtorId][] = [
                    'creditor_id' => $creditorId,
                    'amount' => $amountToTransfer
                ];

                $debtAmount -= $amountToTransfer;
                $creditors[$creditorId] -= $amountToTransfer;

                // Mise à jour pour la boucle suivante
                $debtors[$debtorId] = $debtAmount;
            }
        }

        return $transfers;
    }

    public function refreshWalletState(Wallet $wallet): void
    {
        // 1. Recalculer le montant total du wallet
        $total = $this->walletRepository->calculateTotalBalance($wallet);
        $wallet->setTotalAmount($total);

        // 2. Recalculer qui doit quoi
        $balances = $this->getUserBalances($wallet);

        // 3. Stocker le résultat dans la colonne JSON 'paymentsDue'
        // Plus besoin de recalculer ça à chaque affichage
        $wallet->setPaymentsDue($balances);

        $this->entityManager->flush();
    }

}

