<?php

namespace App\Repository;

use App\Entity\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Group>
 */
class GroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    public function findOneBySlug(string $slug): ?Group
    {
        /** @var Group $group|null */
        $group = $this->createQueryBuilder('g')
            ->andWhere('g.slug = :val')
            ->setParameter('val', $slug)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $group;
    }

    /**
     * @return array<int, Group>
     */
    public function findAllAndOrderByLastExpense(): array
    {
        return $this->createQueryBuilder('g')
            ->leftJoin('g.expenses', 'e')
            ->orderBy('e.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
