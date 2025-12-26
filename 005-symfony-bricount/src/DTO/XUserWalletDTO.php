<?php

namespace App\DTO;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class XUserWalletDTO
{
    #[Assert\NotNull(message: "Veuillez sélectionner un utilisateur")]
    public ?User $user = null;

    #[Assert\NotNull(message: "Veuillez choisir un rôle.")]
    #[Assert\Choice(choices: ['admin', 'user'], message: "Rôle invalide.")]
    public ?string $role = 'user';
}
