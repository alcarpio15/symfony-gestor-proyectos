<?php

namespace App\Repository;

use App\Entity\AreaCoordinacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AreaCoordinacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method AreaCoordinacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method AreaCoordinacion[]    findAll()
 * @method AreaCoordinacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AreaCoordinacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AreaCoordinacion::class);
    }

    public function findAll()
    {
        return $this->createQueryBuilder('ac')
            ->leftJoin('ac.coordinador','gu')
            ->addSelect('gu')
            ->orderBy('ac.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('a')
            ->where('a.something = :value')->setParameter('value', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
