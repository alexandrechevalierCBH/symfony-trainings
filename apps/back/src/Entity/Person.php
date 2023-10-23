<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table('Person')]
class Person
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    private Uuid $id;

    #[ORM\Column]
    private string $firstname;

    #[ORM\Column]
    private string $lastname;

    #[ORM\Column(
        nullable: true,
        unique: true
    )]
    private ?string $email;

    public function __construct(string $firstname, string $lastname, string $email = null)
    {
        $this->id = Uuid::v4();
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    protected function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    protected function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    protected function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getFullname(): string
    {
        return trim($this->firstname).' '.trim($this->lastname);
    }
}
