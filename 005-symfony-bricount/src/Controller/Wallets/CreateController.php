<?php

namespace App\Controller\Wallets;

use App\DTO\WalletDTO;
use App\Entity\User;
use App\Form\WalletType;
use App\Service\WalletService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreateController extends AbstractController
{
    #[Route('wallets/create', name: 'wallets_create', methods: ['GET', 'POST'])]
    public function index(Request $request, WalletService $walletService): Response
    {

        //Instancier un objet DTO et créer un form
        $dto = new WalletDTO();
        $form = $this->createForm(WalletType::class, $dto);

        //traitement de la requête 'post'
        $form->handleRequest($request);

        //derniers controlles de validité et de soumission du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                /** @var User $user */
                $user = $this->getUser();

                //j'appelle la logique métier
                $wallet = $walletService->createWallet($dto, $user);

                $this->addFlash('success', 'Portefeuille créé avec succès !');

                // rediriger automatiquement vers le détail
                return $this->redirectToRoute('wallets_show', ['uid' => $wallet->getUid()]);

            } catch (Exception $e) {
                $this->addFlash('error', 'Erreur lors de la création : ' . $e->getMessage());
                // pas de redirection ici pour debug easy.
            }
        }

        return $this->render('wallets/create/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
