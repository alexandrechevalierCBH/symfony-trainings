<?php

namespace App\Entity;

use App\Services\BalanceCalculator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV7;

#[ORM\Entity]
#[ORM\Table('`group`')]
class Group
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private UuidV7 $id;

    #[ORM\Column]
    private string $label;

    #[ORM\Column(nullable: true)]
    private ?string $description;

    /**
     * @var Collection<int, Person>
     */
    #[ORM\ManyToMany(targetEntity: Person::class, inversedBy: 'groups', cascade: ['persist'])]
    #[ORM\JoinTable(name: 'group_person')]
    private Collection $persons;

    /**
     * @var Collection<int, Expense>
     */
    #[ORM\OneToMany(targetEntity: Expense::class, mappedBy: 'group', cascade: ['remove'])]
    private Collection $expenses;

    /**
     * @param array<Person> $persons
     */
    public function __construct(
        string $label,
        array $persons,
        string $description = null
    ) {
        if (0 === count($persons)) {
            throw new \InvalidArgumentException('should have at least 1 person');
        }

        $this->id = Uuid::v7();
        $this->label = $label;
        $this->description = $description;

        $this->persons = new ArrayCollection($persons);
        $this->expenses = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return Collection<int, Person>
     */
    public function getPersons(): Collection
    {
        return $this->persons;
    }

    public function addPerson(Person $person): void
    {
        if (!$this->persons->contains($person)) {
            $this->persons->add($person);
        }
    }

    /**
     * @param array<Person> $members
     */
    public function addPersons(array $members): void
    {
        foreach ($members as $member) {
            $this->addPerson($member);
        }
    }

    /**
     * @return Collection<int, Expense>
     */
    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    public function getTotalExpenses(): int
    {
        $expenses = $this->getExpenses()->map(static fn (Expense $expense) => $expense->getAmount());

        return array_sum($expenses->toArray());
    }

    public function addExpense(Expense $expense): void
    {
        if (!$this->expenses->contains($expense)) {
            $this->expenses->add($expense);
        }
    }

    /**
     * @param array<Expense> $expensesList
     */
    public function addExpenses(array $expensesList): void
    {
        foreach ($expensesList as $expense) {
            $this->addExpense($expense);
        }
    }

    /**
     * @return array<Balance>
     */
    public function getBalances(): array
    {
        return BalanceCalculator::getBalances($this);
    }
}
