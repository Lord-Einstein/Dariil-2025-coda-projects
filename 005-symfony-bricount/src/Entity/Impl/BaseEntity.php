<?php

namespace App\Entity\Impl;

use App\Entity\User;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

abstract class BaseEntity
{
    #[ORM\Column(name: 'created_date', type: Types::DATETIME_MUTABLE, nullable: false, options: ['default' => "CURRENT_TIMESTAMP"])]
    protected DateTime $createdDate;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    protected User $createdBy;

    #[ORM\Column(name: 'updated_date', type: Types::DATETIME_MUTABLE, nullable: true)]
    protected null|DateTime $updatedDate;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    protected null|User $updatedBy;

    #[ORM\Column(name: 'deleted_date', type: Types::DATETIME_MUTABLE, nullable: true)]
    protected null|DateTime $deletedDate;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    protected null|User $deletedBy;

    #[ORM\Column(name: 'is_deleted', type: Types::BOOLEAN, nullable: false, options: ['default' => false])]
    protected bool $isDeleted = false;

    public function getCreatedDate(): DateTime
    {
        return $this->createdDate;
    }

    public function setCreatedDate(DateTime $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedDate(): ?DateTime
    {
        return $this->updatedDate;
    }

    public function setUpdatedDate(?DateTime $updatedDate): self
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?User $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getDeletedDate(): ?DateTime
    {
        return $this->deletedDate;
    }

    public function setDeletedDate(?DateTime $deletedDate): self
    {
        $this->deletedDate = $deletedDate;

        return $this;
    }

    public function getDeletedBy(): ?User
    {
        return $this->deletedBy;
    }

    public function setDeletedBy(?User $deletedBy): self
    {
        $this->deletedBy = $deletedBy;

        return $this;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

}
