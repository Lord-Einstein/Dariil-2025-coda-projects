<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ExpenseDTO
{
    #[Assert\NotNull(message: "Le montant est obligatoire")]
    #[Assert\Positive(message: "Le montant doit être positif")]
    public ?float $amount = null;

    #[Assert\NotBlank(message: "La description est obligatoire")]
    #[Assert\Length(min: 3, minMessage: "La description doit faire au moins 3 caractères")]
    public ?string $description = '';

    #[Assert\NotBlank(message: "Veuillez choisir une icone !")]
    public ?string $icon = 'fa-solid fa-receipt';

}

