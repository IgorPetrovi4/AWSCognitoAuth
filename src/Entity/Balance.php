<?php

namespace App\Entity;

use App\Repository\BalanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BalanceRepository::class)]
class Balance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'balances')]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Currency should not be blank.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Currency cannot be longer than {{ limit }} characters"
    )]
    private ?string $currency = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 18, scale: 3)]
    #[Assert\NotBlank(message: "Amount should not be blank.")]
    #[Assert\Regex(
        pattern: "/^\d+(\.\d{1,3})?$/",
        message: "Amount should be a number with up to 3 decimal places."
    )]
    private ?string $amount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }
}
