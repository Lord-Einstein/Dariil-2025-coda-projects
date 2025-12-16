<?php

namespace App\Entity;

use App\Entity\Impl\BaseEntity;
use App\Repository\XUserWalletRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: XUserWalletRepository::class)]
class XUserWallet extends BaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'xUserWallets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Wallet $wallet = null;

    #[ORM\ManyToOne(inversedBy: 'xUserWallets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $targetUser = null;

    #[ORM\Column(length: 50, options: ['default' => 'user'])]
    private ?string $role = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(?Wallet $wallet): static
    {
        $this->wallet = $wallet;

        return $this;
    }

    public function getTargetUser(): ?User
    {
        return $this->targetUser;
    }

    public function setTargetUser(?User $targetUser): static
    {
        $this->targetUser = $targetUser;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }
}
