<?php

namespace App\Repository;

use App\Entity\SolicitudServicio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SolicitudServicio|null find($id, $lockMode = null, $lockVersion = null)
 * @method SolicitudServicio|null findOneBy(array $criteria, array $orderBy = null)
 * @method SolicitudServicio[]    findAll()
 * @method SolicitudServicio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
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
            ->leftJoin('ss.autor','autor_gu')
            ->andWhere('autor_gu.id = :id')
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
