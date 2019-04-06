<?php

namespace App\Repository;

use App\Entity\SolicitudTarea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SolicitudTarea|null find($id, $lockMode = null, $lockVersion = null)
 * @method SolicitudTarea|null findOneBy(array $criteria, array $orderBy = null)
 * @method SolicitudTarea[]    findAll()
 * @method SolicitudTarea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitudTareaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SolicitudTarea::class);
    }

    public function findAllWithRequerimentJoin() {
        return $this->createQueryBuilder('st')
            ->leftJoin('st.requerimiento','sr')
            ->leftJoin('st.desarrollador','st_dev')
            ->leftJoin('sr.area','ac')
            ->addOrderBy('ac.id', 'ASC')
            ->addOrderBy('st.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllByAreaCoordinatorWithJoins($ac_id) {
        return $this->createQueryBuilder('st')
            ->leftJoin('st.requerimiento','sr')
            ->leftJoin('st.desarrollador','st_dev')
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

    public function findByUserWithRequerimentJoin($dev_id) {
        return $this->createQueryBuilder('st')
            ->leftJoin('st.requerimiento','sr')
            ->leftJoin('st.desarrollador','st_dev')
            ->leftJoin('sr.area','ac')
            ->andWhere('st_dev.id = :dev_id')
            ->setParameter('dev_id', $dev_id)
            ->addOrderBy('st.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOverdue(\DateTime $due_date) {
        return $this->createQueryBuilder('st')
            ->andWhere(':due_date >= st.fechaEntregaEstimada')
            ->andWhere('st.estado != :ejecutandose')
            ->setParameters(array(
                'due_date' => $due_date,
                'ejecutandose' => 1
            ))
            ->addOrderBy('st.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function countUnfinished() {
        $qb = $this->createQueryBuilder('st');

        return $qb->select('COUNT(st.id)')
            ->andWhere('st.estado != :por_revisar')
            ->andWhere('st.estado != :completado')
            ->andWhere('st.estado != :cancelado')
            ->setParameters(array(
                'completado' => 7,
                'cancelado' => 6
            ))
            ->getQuery()
            ->getgetSingleScalarResult();
    }

    public function countUncanceled() {
        $qb = $this->createQueryBuilder('st');

        return $qb->select('COUNT(st.id)')
            ->andWhere('st.estado != :cancelado')
            ->setParameters(array(
                'cancelado' => 6
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
