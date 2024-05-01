<?php

namespace App\Entity;

use App\Repository\BalanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AppAssert;

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
    #[AppAssert\Enum(
        [
            'values' => ['USDT', 'Gold'],
            'message' => "Invalid currency. Allowed values are 'USDT', 'Gold'"
        ]
    )]
    private ?string $currency = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 18, scale: 3)]
    #[Assert\NotBlank(message: "Amount should not be blank.")]
    #[AppAssert\DecimalPrecision(
        [
            'message' => "Amount should be a number with up to 3 decimal places.",
            'invalidMessage' => "Amount should be a valid number.",
            'maxMessage' => "Amount should not be greater than 999999999999999.999.",
            'max' => 999999999999999.999
        ]
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
