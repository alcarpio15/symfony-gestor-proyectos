<?php

namespace App\Repository;

use App\Entity\SolicitudRequerimientos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SolicitudRequerimientos|null find($id, $lockMode = null, $lockVersion = null)
 * @method SolicitudRequerimientos|null findOneBy(array $criteria, array $orderBy = null)
 * @method SolicitudRequerimientos[]    findAll()
 * @method SolicitudRequerimientos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitudRequerimientosRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SolicitudRequerimientos::class);
    }

    public function findAllWithServiceJoin() {
        return $this->createQueryBuilder('sr')
            ->leftJoin('sr.servicio','ss')
            ->orderBy('sr.id', 'ASC')
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
