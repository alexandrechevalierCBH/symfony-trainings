<?php

namespace App\Repository;

use App\Entity\Expense;
use App\Entity\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Expense>
 */
class ExpenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Expense::class);
    }

    /**
     * @return ?array<int, Expense>
     */
    public function findAndPaginateExpenses(Group $group, int $page, int $step): array|null
    {
        return $this->createQueryBuilder('e')
            ->where('e.group = :group')
            ->setParameter('group', $group->getId(), UuidType::NAME)
            ->setFirstResult($page * $step - $step)
            ->setMaxResults($step)
            ->orderBy('e.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByUuid(Uuid $id): ?Expense
    {
        /** @var Expense $expense|null */
        $expense = $this->createQueryBuilder('expense')
            ->where('expense.id = :id')
            ->setParameter('id', $id, UuidType::NAME)
            ->getQuery()
            ->getOneOrNullResult();

        return $expense;
    }
}
