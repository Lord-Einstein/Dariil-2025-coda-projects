<?php

namespace App\Controller\Wallets;

use App\Entity\User;
use App\Entity\XUserWallet;
use App\Repository\ExpenseRepository;
use App\Service\WalletService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/wallets/members')]
class MembersController extends AbstractController
{
    #[Route('/promote/{id}', name: 'wallet_member_promote')]
    public function promote(XUserWallet $relation, EntityManagerInterface $em): Response
    {

        /** @var User $user */
        $user = $this->getUser();

        $relation->setRole('admin');
        $relation->setUpdatedBy($user);
        $relation->setUpdatedDate(new DateTime());
        $em->flush();

        $this->addFlash('success', "Le membre est passé Admin.");
        return $this->redirectToRoute('wallets_show', ['uid' => $relation->getWallet()->getUid()]);
    }

    #[Route('/demote/{id}', name: 'wallet_member_demote')]
    public function demote(XUserWallet $relation, EntityManagerInterface $em): Response
    {
        // On empêche de rétrograder le propriétaire (Owner)
        if ($relation->getWallet()->getOwner() === $relation->getTargetUser()) {
            $this->addFlash('danger', "Impossible de rétrograder le créateur du portefeuille.");
            return $this->redirectToRoute('wallets_show', ['uid' => $relation->getWallet()->getUid()]);
        }

        /** @var User $user */
        $user = $this->getUser();

        $relation->setRole('user');
        $relation->setUpdatedBy($user);
        $relation->setUpdatedDate(new DateTime());
        $em->flush();

        $this->addFlash('warning', "Le membre est repassé Utilisateur simple.");
        return $this->redirectToRoute('wallets_show', ['uid' => $relation->getWallet()->getUid()]);
    }

    #[Route('/remove/{id}', name: 'wallet_member_remove', methods: ['POST'])]
    public function remove(
        Request                $request,
        XUserWallet            $relation,
        EntityManagerInterface $em,
        ExpenseRepository      $expenseRepo,
        WalletService          $walletService,
    ): Response
    {
        $wallet = $relation->getWallet();
        $userToRemove = $relation->getTargetUser();

        // Sécurité de base
        if ($wallet->getOwner() === $userToRemove) {
            $this->addFlash('danger', "On ne peut pas supprimer le créateur du wallet !");
            return $this->redirectToRoute('wallets_show', ['uid' => $wallet->getUid()]);
        }

        // 1. Gestion du choix : Supprimer les dépenses aussi ?
        $deleteExpenses = $request->request->get('delete_expenses') === 'yes';

        if ($deleteExpenses) {
            $expenses = $expenseRepo->findActiveExpensesByWalletAndUser($wallet, $userToRemove);
            foreach ($expenses as $expense) {
                // Soft Delete de la dépense
                $expense->setIsDeleted(true);
                $expense->setDeletedDate(new DateTime());
                $expense->setDeletedBy($this->getUser());
            }
            $this->addFlash('info', count($expenses) . " dépenses ont été archivées.");
        }

        // 2. Soft Delete de la relation (XUserWallet)
        // On utilise tes méthodes de BaseEntity
        $relation->setIsDeleted(true);
        $relation->setDeletedDate(new DateTime());
        $relation->setDeletedBy($this->getUser());

        $em->flush();

        $walletService->refreshWalletState($wallet);

        $this->addFlash('success', "Le membre a été retiré du portefeuille.");
        return $this->redirectToRoute('wallets_show', ['uid' => $wallet->getUid()]);
    }
}

