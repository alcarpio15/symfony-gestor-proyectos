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
            ->leftJoin('sr.area','ac')
            ->addOrderBy('ac.id', 'ASC')
            ->addorderBy('sr.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllByAreaCoordinatorWithJoins($ac_id) {
        return $this->createQueryBuilder('sr')
            ->leftJoin('sr.servicio','ss')
            ->leftJoin('sr.area','ac')
            ->leftJoin('ac.coordinador','ac_coord')
            ->andWhere('ac_coord.id = :ac_id')
            ->setParameter('ac_id', $ac_id)
            ->addOrderBy('ac.id', 'ASC')
            ->addOrderBy('sr.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function countUnfinished(){
        $qb = $this->createQueryBuilder('sr');

        return $qb->select('COUNT(st.id)')
            ->andWhere('sr.estado != :completado')
            ->andWhere('sr.estado != :cancelado')
            ->setParameters(array(
                'completado' => 7,
                'cancelado' => 4
            ))
            ->getQuery()
            ->getgetSingleScalarResult();
    }

    
    public function countUncanceled(){
        $qb = $this->createQueryBuilder('sr');

        return $qb->select('COUNT(st.id)')
            ->andWhere('sr.estado != :cancelado')
            ->setParameters(array(
                'cancelado' => 4
            ))
            ->getQuery()
            ->getgetSingleScalarResult();
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
