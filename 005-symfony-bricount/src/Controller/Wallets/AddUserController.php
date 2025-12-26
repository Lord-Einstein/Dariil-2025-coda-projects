<?php

namespace App\Controller\Wallets;

use App\DTO\XUserWalletDTO;
use App\Entity\User;
use App\Entity\Wallet;
use App\Form\XUserWalletType;
use App\Service\WalletService;
use App\Service\XUserWalletService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AddUserController extends AbstractController
{
    #[Route('wallets/{uid}/add-user', name: 'wallets_add_user', methods: ['GET', 'POST'])]
    public function index(
        #[MapEntity(mapping: ['uid' => 'uid'])] Wallet $wallet,
        Request                                        $request,
        WalletService                                  $walletService,
        XUserWalletService                             $xUserWalletService
    ): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $redirectError = null;
        $flashType = 'danger';
        $availableUsers = [];

        $access = $walletService->getUserAccessOnWallet($currentUser, $wallet);

        // Vérifier état d'Admin
        if (!$access || $access->getRole() !== 'admin') {
            $redirectError = "Seuls les administrateurs peuvent inviter des membres.";
        } // Vérif Utilisateurs simples
        else {
            $availableUsers = $walletService->findAvailableUsersForWallet($wallet);
            if (empty($availableUsers)) {
                $redirectError = "Tous les utilisateurs enregistrés sont déjà dans ce portefeuille !";
                $flashType = 'info';
            }
        }

        // Si une erreur bloquante existe, on sort ici.
        if ($redirectError) {
            $this->addFlash($flashType, $redirectError);
            return $this->redirectToRoute('wallets_show', ['uid' => $wallet->getUid()]);
        }

        //Formulaire
        $dto = new XUserWalletDTO();
        $form = $this->createForm(XUserWalletType::class, $dto, [
            'available_users' => $availableUsers
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $xUserWalletService->create($wallet, $dto->user, $dto->role, $currentUser);

            $this->addFlash('success', "L'utilisateur a été ajouté au portefeuille.");
            return $this->redirectToRoute('wallets_show', ['uid' => $wallet->getUid()]);
        }

        return $this->render('wallets/add_user/index.html.twig', [
            'form' => $form->createView(),
            'wallet' => $wallet
        ]);
    }
}
