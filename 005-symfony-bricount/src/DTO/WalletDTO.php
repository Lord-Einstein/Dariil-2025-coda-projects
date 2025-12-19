<?php

namespace App\DTO;

use App\Entity\Wallet;
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

    public static function fromEntity(Wallet $wallet): self
    {
        $dto = new self();
        // Mapping : On prend le Label de l'entité pour le mettre dans le Name de la DTO
        $dto->name = $wallet->getLabel();

        return $dto;
    }
}
