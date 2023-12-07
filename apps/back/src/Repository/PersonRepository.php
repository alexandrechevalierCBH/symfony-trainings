<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Person>
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function findOneByUuid(Uuid $payerId): Person
    {
        $result = $this->createQueryBuilder('p')
            ->where('p.id = :uuid')
            ->setParameter('uuid', $payerId, UuidType::NAME)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$result instanceof Person) {
            throw new NotFoundHttpException('Could not get the payer');
        }

        return $result;
    }

    /**
     * @param array<int, Uuid> $personsUuid
     *
     * @return array<int, Person>
     */
    public function findPersonsByUuid(array $personsUuid): array
    {
        $ids = array_map(static fn ($uuid) => $uuid->toBinary(), $personsUuid);

        $persons = $this->createQueryBuilder('p')
            ->where('p.id IN (:personsUuid)')
            ->setParameter('personsUuid', $ids)
            ->getQuery()
            ->getResult();

        return $persons;
    }
}
