<?php

namespace App\Controller\Settings;

use App\Entity\User;
use App\Form\ProfileType;
use App\Repository\ExpenseRepository;
use App\Repository\WalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/settings', name: 'settings', methods: ['GET', 'POST'])]
    public function index(
        Request                $request,
        EntityManagerInterface $em,
        WalletRepository       $walletRepository,
        ExpenseRepository      $expenseRepository
    ): Response
    {

        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            $this->addFlash('success', 'Profil mis à jour !');
            return $this->redirectToRoute('settings');
        }

        // Récupération des stats via les méthodes corrigées
        $stats = [
            'owned' => $walletRepository->countOwnedWallets($user),
            'joined' => $walletRepository->countJoinedWallets($user),
            'connections' => $walletRepository->countUniqueConnections($user),
            'spent' => $expenseRepository->getTotalSpentByUser($user),
        ];

        // Liste d'avatars pour la grille
        $menAvatars = [
            'https://cdn3d.iconscout.com/3d/premium/thumb/man-avatar-6299539-5187871.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/woman-avatar-6299538-5187870.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/boy-avatar-6299533-5187865.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/girl-avatar-6299534-5187866.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/black-man-avatar-6299542-5187874.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/punk-man-avatar-6299537-5187869.png',
        ];

        $womenAvatars = [
            'https://cdn3d.iconscout.com/3d/premium/thumb/black-woman-avatar-6299541-5187873.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/girl-avatar-6299534-5187866.png',
        ];

        $avatars = $user->getGender() == 'M' ? $menAvatars : $womenAvatars;

        return $this->render('settings/index/index.html.twig', [
            'form' => $form->createView(),
            'stats' => $stats,
            'avatars' => $avatars,
        ]);
    }
}
