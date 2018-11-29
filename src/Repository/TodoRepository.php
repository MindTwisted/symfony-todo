<?php

namespace App\Repository;

use App\Entity\Todo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Todo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Todo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Todo[]    findAll()
 * @method Todo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Todo::class);
    }

    public function findByToArray(Array $criteria): Array
    {
        $qb = $this->createQueryBuilder('t')
            ->select('t');

        if (isset($criteria['categoryId']))
        {
            $qb = $qb->andWhere('t.category = :categoryId')->setParameter('categoryId', $criteria['categoryId']);
        }

        if (isset($criteria['userId']))
        {
            $qb = $qb->andWhere('t.user = :userId')->setParameter('userId', $criteria['userId']);
        }

        $qb = $qb->getQuery()->getArrayResult();

        return $qb;
    }

    public function findOneByToArray(Array $criteria): Array
    {
        $qb = $this->createQueryBuilder('t')
            ->select('t');

        if (isset($criteria['id']))
        {
            $qb = $qb->andWhere('t.id = :id')->setParameter('id', $criteria['id']);
        }

        if (isset($criteria['userId']))
        {
            $qb = $qb->andWhere('t.user = :userId')->setParameter('userId', $criteria['userId']);
        }

        $qb = $qb->setMaxResults(1)->getQuery()->getArrayResult();

        return $qb;
    }
}
