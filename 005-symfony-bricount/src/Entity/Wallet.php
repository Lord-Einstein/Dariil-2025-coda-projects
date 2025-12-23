<?php

namespace App\Entity;

use App\Entity\Impl\BaseEntity;
use App\Repository\WalletRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: WalletRepository::class)]
class Wallet extends BaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::GUID, unique: true)]
    private ?string $uid = null;

    #[ORM\Column(options: ['default' => 0])]
    private ?int $totalAmount = 0;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(type: Types::JSON, options: ['default' => '[]'])]
    private array $paymentsDue = [];

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $lastSettlementDate = null;

    /**
     * @var Collection<int, Expense>
     */
    #[ORM\OneToMany(targetEntity: Expense::class, mappedBy: 'wallet')]
    private Collection $expenses;

    /**
     * @var Collection<int, XUserWallet>
     */
    #[ORM\OneToMany(targetEntity: XUserWallet::class, mappedBy: 'wallet', orphanRemoval: true)]
    private Collection $xUserWallets;

    public function __construct()
    {
        $this->uid = Uuid::v4()->toRfc4122();
        $this->expenses = new ArrayCollection();
        $this->xUserWallets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(string $uid): static
    {
        $this->uid = $uid;

        return $this;
    }

    public function getTotalAmount(): ?int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalAmount): static
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getPaymentsDue(): array
    {
        return $this->paymentsDue;
    }

    public function setPaymentsDue(array $paymentsDue): static
    {
        $this->paymentsDue = $paymentsDue;

        return $this;
    }

    public function getLastSettlementDate(): ?DateTimeInterface
    {
        return $this->lastSettlementDate;
    }

    public function setLastSettlementDate(?DateTimeInterface $lastSettlementDate): static
    {
        $this->lastSettlementDate = $lastSettlementDate;

        return $this;
    }

    /**
     * @return Collection<int, Expense>
     */
    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    public function addExpense(Expense $expense): static
    {
        if (!$this->expenses->contains($expense)) {
            $this->expenses->add($expense);
            $expense->setWallet($this);
        }

        return $this;
    }

    public function removeExpense(Expense $expense): static
    {
        if ($this->expenses->removeElement($expense)) {
            // set the owning side to null (unless already changed)
            if ($expense->getWallet() === $this) {
                $expense->setWallet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, XUserWallet>
     */
    public function getXUserWallets(): Collection
    {
        return $this->xUserWallets;
    }

    public function addXUserWallet(XUserWallet $xUserWallet): static
    {
        if (!$this->xUserWallets->contains($xUserWallet)) {
            $this->xUserWallets->add($xUserWallet);
            $xUserWallet->setWallet($this);
        }

        return $this;
    }

    public function removeXUserWallet(XUserWallet $xUserWallet): static
    {
        if ($this->xUserWallets->removeElement($xUserWallet)) {
            // set the owning side to null (unless already changed)
            if ($xUserWallet->getWallet() === $this) {
                $xUserWallet->setWallet(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->createdBy;
    }

    /**
     * Récupère tous les membres actifs (Owner + Invités)
     * @return User[]
     */
    public function getMembers(): array
    {
        $members = [];

        // ajout du propriétaire et j'utilise l'ID comme clé pour éviter les doublons facilement
//        if ($this->getOwner()) {
//            $members[$this->getOwner()->getId()] = $this->getOwner();
//        }

        foreach ($this->getXUserWallets() as $xUserWallet) {
            if (!$xUserWallet->isDeleted() && $xUserWallet->getTargetUser()) {
                $user = $xUserWallet->getTargetUser();
                $members[$user->getId()] = $user;
            }
        }

        return $members;
    }

}
