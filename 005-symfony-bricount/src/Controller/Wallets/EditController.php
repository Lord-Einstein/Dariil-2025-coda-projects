<?php

namespace App\Controller\Wallets;

use App\DTO\WalletDTO;
use App\Entity\User;
use App\Entity\Wallet;
use App\Form\WalletType;
use App\Service\WalletService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EditController extends AbstractController
{
    #[Route('/wallets/{uid}/edit', name: 'wallets_edit', methods: ['GET', 'POST'])]
    public function index(
        #[MapEntity(mapping: ['uid' => 'uid'])] Wallet $wallet,
        Request                                        $request,
        WalletService                                  $walletService
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $access = $walletService->getUserAccessOnWallet($user, $wallet);

        if (!$access || $access->getRole() !== 'admin') { //seul l'admin peut éditer le wallet
            $this->addFlash('danger', "Vous n'avez pas les droits de modification sur ce portefeuille.");
            return $this->redirectToRoute('wallets_show', ['uid' => $wallet->getUid()]);
        }

        $dto = WalletDTO::fromEntity($wallet);

        $form = $this->createForm(WalletType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $walletService->updateWallet($wallet, $dto, $user);
            $this->addFlash('success', 'Le portefeuille a été modifié avec succès.');

            return $this->redirectToRoute('wallets_show', ['uid' => $wallet->getUid()]);
        }

        return $this->render('wallets/edit/index.html.twig', [
            'form' => $form->createView(),
            'wallet' => $wallet
        ]);
    }
}
