<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV7;

#[ORM\Entity]
#[ORM\Table('`person`')]
class Person
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private UuidV7 $id;

    #[ORM\Column]
    private string $firstname;

    #[ORM\Column]
    private string $lastname;

    #[ORM\Column(
        nullable: true,
        unique: true
    )]
    private ?string $email;

    /**
     * @var Collection<int, Group>
     */
    #[ORM\ManyToMany(targetEntity: Group::class, mappedBy: 'persons')]
    private Collection $groups;

    /**
     * @var Collection<int, Expense>
     */
    #[ORM\ManyToMany(targetEntity: Expense::class, mappedBy: 'beneficiaries')]
    private Collection $expenses;

    public function __construct(
        string $firstname,
        string $lastname,
        string $email = null
    ) {
        $this->id = Uuid::v7();
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;

        $this->groups = new ArrayCollection();
        $this->expenses = new ArrayCollection();
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

    /**
     * @return Collection<int, Group>
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    /**
     * @return Collection<int, Expense>
     */
    public function getExpenses(): Collection
    {
        return $this->expenses;
    }
}
