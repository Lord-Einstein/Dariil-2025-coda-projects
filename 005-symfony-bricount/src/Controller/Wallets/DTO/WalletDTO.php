<?php

namespace App\Controller\Wallets\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class WalletDTO
{
    #[Assert\NotBlank(message: "Le nom du portefeuille ne peut pas être vide.")]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: "Le nom doit faire au moins {{ limit }} caractères.",
        maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères."
    )]
    public ?string $name = null;
}
