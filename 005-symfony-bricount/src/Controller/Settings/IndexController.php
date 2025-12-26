<?php

namespace App\Controller\Settings;

use App\DTO\ProfileDTO;
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


        $dto = new ProfileDTO();
        $dto->name = $user->getName();
        $dto->gender = $user->getGender();
        $dto->avatar = $user->getAvatar();

        $openProfileModal = true;

        $form = $this->createForm(ProfileType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setName($dto->name);
            $user->setGender($dto->gender);
            $user->setAvatar($dto->avatar);

            $em->flush();

            $this->addFlash('success', 'Profil mis à jour !');
            return $this->redirectToRoute('settings');
        }

        $openProfileModal = $form->isSubmitted() && !$form->isValid();

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
            'https://cdn3d.iconscout.com/3d/premium/thumb/curly-hair-man-with-glasses-3d-icon-png-download-5823020.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/dreadlocked-man-3d-icon-png-download-5823057.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/curly-hair-man-3d-icon-png-download-5823051.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/man-with-glasses-3d-icon-png-download-5823041.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/short-hair-man-with-glasses-3d-icon-png-download-5823072.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/man-3d-icon-png-download-5823043.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/man-with-beard-3d-icon-png-download-5823044.png',

            'https://cdn3d.iconscout.com/3d/premium/thumb/ronin-red-samurai-suits-3d-icon-png-download-8382309.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/chinese-cat-in-samurai-suit-3d-icon-png-download-8382307.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/samurai-themed-kubricks-samurai-suits-3d-icon-png-download-8382304.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/sengoku-basara-nendoroids-samurai-suits-3d-icon-png-download-8382301.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/tamashii-nations-figuarts-samurai-suits-3d-icon-png-download-8382302.png',

            'https://cdn3d.iconscout.com/3d/premium/thumb/ramen-3d-icon-png-download-4847193.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/takoyaki-3d-icon-png-download-8436638.png',

        ];

        $womenAvatars = [
            'https://cdn3d.iconscout.com/3d/premium/thumb/colombian-3d-icon-png-download-5251135.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/with-glasses-3d-icon-png-download-5823066.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/woman-with-hijab-3d-icon-png-download-5823033.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/woman-with-hijab-and-glasses-3d-icon-png-download-5823034.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/long-hair-woman-with-hat-3d-icon-png-download-5823023.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/long-hair-woman-with-hat-3d-icon-png-download-5823025.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/long-hair-woman-with-hat-3d-icon-png-download-5823029.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/long-hair-woman-with-glasses-3d-icon-png-download-5823031.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/long-hair-woman-3d-icon-png-download-5823027.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/long-hair-woman-3d-icon-png-download-5823024.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/long-hair-woman-3d-icon-png-download-5823022.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/female-avatars-3d-icon-png-download-10764486.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/long-hair-woman-with-glasses-3d-icon-png-download-5823021.png',

            'https://cdn3d.iconscout.com/3d/premium/thumb/ronin-red-samurai-suits-3d-icon-png-download-8382309.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/chinese-cat-in-samurai-suit-3d-icon-png-download-8382307.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/samurai-themed-kubricks-samurai-suits-3d-icon-png-download-8382304.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/sengoku-basara-nendoroids-samurai-suits-3d-icon-png-download-8382301.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/tamashii-nations-figuarts-samurai-suits-3d-icon-png-download-8382302.png',

            'https://cdn3d.iconscout.com/3d/premium/thumb/ramen-3d-icon-png-download-4847193.png',
            'https://cdn3d.iconscout.com/3d/premium/thumb/takoyaki-3d-icon-png-download-8436638.png',
        ];

        $avatars = $user->getGender() == 'M' ? $menAvatars : $womenAvatars;

        return $this->render('settings/index/index.html.twig', [
            'form' => $form->createView(),
            'stats' => $stats,
            'avatars' => $avatars,
            'openProfileModal' => $openProfileModal,
        ]);
    }
}
