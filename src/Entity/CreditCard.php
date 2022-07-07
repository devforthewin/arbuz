<?php

namespace App\Entity;

use App\Repository\CreditCardRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CreditCardRepository::class)]
class CreditCard
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $number;

    /**
     * @Assert\NotBlank
     * @Assert\Positive
     * @Assert\Length(
     *      min = 2,
     *      max = 3,
     *      minMessage = "11111Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "22222Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    #[ORM\Column(type: 'integer')]
    private $pin;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getPin(): ?int
    {
        return $this->pin;
    }

    public function setPin(int $pin): self
    {
        $this->pin = $pin;

        return $this;
    }
}
