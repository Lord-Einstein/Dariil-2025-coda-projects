<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ProfileDTO
{
    #[Assert\NotBlank(message: 'Veuillez entrer un nom.')]
    #[Assert\Length(
        min: 2,
        minMessage: 'Votre nom doit contenir au moins {{ limit }} caractères.',
        max: 50
    )]
    public ?string $name = null;

    #[Assert\NotBlank(message: 'Veuillez choisir un genre.')]
    #[Assert\Choice(choices: ['M', 'F'], message: 'Genre invalide.')]
    public ?string $gender = null;

    #[Assert\NotBlank(message: 'Veuillez choisir un avatar.')]
    public ?string $avatar = null;
}
