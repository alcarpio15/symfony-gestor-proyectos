<?php

namespace App\Repository;

use App\Entity\HistorialTarea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method HistorialTarea|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistorialTarea|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistorialTarea[]    findAll()
 * @method HistorialTarea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistorialTareaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HistorialTarea::class);
    }

    public function findAllChrono($asc = false)
    {
        if ($asc) {
            return $this->createQueryBuilder('th')
                ->orderBy('th.creado', 'ASC')
                ->getQuery()
                ->getResult()
            ;
        } else {
            return $this->createQueryBuilder('th')
                ->orderBy('th.creado', 'DESC')
                ->getQuery()
                ->getResult()
            ;
        }  
    }

    public function findAllGroupedByTask(){
        return $this->createQueryBuilder('th')
            ->leftJoin('th.tarea','st')
            ->addOrderBy('st.id', 'ASC')
            ->addOrderBy('th.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllByOneTask($st_id){
        return $this->createQueryBuilder('th')
            ->leftJoin('th.tarea','st')
            ->andWhere('st.id = :st_id')
            ->setParameter('st_id', $st_id)
            ->getQuery()
            ->getResult()
        ;
    }

    

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('h')
            ->where('h.something = :value')->setParameter('value', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
