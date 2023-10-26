<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
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
    #[ManyToMany(targetEntity: Person::class, inversedBy: 'groups')]
    #[JoinTable(name: 'group_person')]
    private Collection $persons;

    public function __construct(
        string $label,
        string $description = null
    ) {
        $this->id = Uuid::v7();
        $this->label = $label;
        $this->description = $description;

        $this->persons = new ArrayCollection();
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
        $this->persons->add($person);
    }
}
