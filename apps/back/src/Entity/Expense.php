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
    private string $description;

    #[ORM\Column(type: 'float')]
    private float $amount;

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
    public function __construct(string $description, Group $group, float $amount, Person $payer, array $beneficiaries)
    {
        if (0 === count($beneficiaries)) {
            throw new \InvalidArgumentException('should have at least 1 beneficiary');
        }

        $this->id = Uuid::v7();
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getPayer(): Person
    {
        return $this->payer;
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

    public function getUnitaryShared(): float
    {
        return floatval(number_format($this->amount / count($this->beneficiaries), 2));
    }

    public function getUserShare(Person $person): float
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
