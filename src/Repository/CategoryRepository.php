<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findByUserJoinedToTodos($id): Array
    {
        return $this->createQueryBuilder('c')
            ->select('c, t')
            ->leftJoin('c.todos', 't')
            ->andWhere('c.user = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function findByToArray(Array $criteria): Array
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c');

        if (isset($criteria['userId']))
        {
            $qb = $qb->andWhere('c.user = :userId')->setParameter('userId', $criteria['userId']);
        }

        $qb = $qb->getQuery()->getArrayResult();

        return $qb;
    }

    public function findOneByToArray(Array $criteria): Array
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c');

        if (isset($criteria['id']))
        {
            $qb = $qb->andWhere('c.id = :id')->setParameter('id', $criteria['id']);
        }

        if (isset($criteria['userId']))
        {
            $qb = $qb->andWhere('c.user = :userId')->setParameter('userId', $criteria['userId']);
        }

        $qb = $qb->setMaxResults(1)->getQuery()->getArrayResult();

        return $qb;
    }
}
