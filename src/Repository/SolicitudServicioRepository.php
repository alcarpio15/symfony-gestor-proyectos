<?php

namespace App\Repository;

use App\Entity\SolicitudServicio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SolicitudServicioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SolicitudServicio::class);
    }

    public function findAll()
    {
        return $this->createQueryBuilder('ss')
            ->leftJoin('ss.autor','gu')
            ->orderBy('ss.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllByUser($id)
    {
        return $this->createQueryBuilder('ss')
            ->andWhere('ss.autor = :id')
            ->setParameter('id', $id)
            ->orderBy('ss.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    /*

    public function findBySomething($value)
    {
        return $this->createQueryBuilder('s')
            ->where('s.something = :value')->setParameter('value', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
