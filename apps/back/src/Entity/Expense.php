<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV7;

#[ORM\Entity]
#[ORM\Table('`expense`')]
class Expense
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private UuidV7 $id;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private string $description;

    #[ORM\Column(type: 'integer')]
    private int $amount;

    #[ORM\ManyToOne(targetEntity: Person::class)]
    #[ORM\JoinColumn(name: 'payer_id')]
    private Person $payer;

    /**
     * @var Collection<int, Person>
     */
    #[ORM\ManyToMany(targetEntity: Person::class, inversedBy: 'expenses')]
    #[ORM\JoinTable(name: 'expense_person')]
    private Collection $beneficiaries;

    #[ORM\ManyToOne(targetEntity: Group::class, inversedBy: 'expenses', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'group_id', referencedColumnName: 'id')]
    private Group $group;

    /**
     * @param array<Person> $beneficiaries
     */
    public function __construct(string $description, Group $group, int $amount, Person $payer, array $beneficiaries)
    {
        if (0 === count($beneficiaries)) {
            throw new \InvalidArgumentException('should have at least 1 beneficiary');
        }

        $this->id = Uuid::v7();
        $this->createdAt = new \DateTimeImmutable();
        $this->description = $description;
        $this->group = $group;
        $this->amount = $amount;
        $this->payer = $payer;

        $this->beneficiaries = new ArrayCollection($beneficiaries);
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getPayer(): Person
    {
        return $this->payer;
    }

    public function setPayer(Person $payer): void
    {
        $this->payer = $payer;
    }

    /**
     * @return Collection<int, Person>
     */
    public function getBeneficiaries(): Collection
    {
        return $this->beneficiaries;
    }

    public function getGroup(): Group
    {
        return $this->group;
    }

    public function getUnitaryShared(): int
    {
        return intval($this->amount / count($this->beneficiaries));
    }

    public function getUserShare(Person $person): int
    {
        $balance = 0;

        if ($person === $this->payer) {
            $balance += $this->amount;
        }

        if ($this->beneficiaries->contains($person)) {
            $balance -= $this->getUnitaryShared();
        }

        return $balance;
    }
}
