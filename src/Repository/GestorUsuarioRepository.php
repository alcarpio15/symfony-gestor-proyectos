<?php

namespace App\Repository;

use App\Entity\GestorUsuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GestorUsuario|null find($id, $lockMode = null, $lockVersion = null)
 * @method GestorUsuario|null findOneBy(array $criteria, array $orderBy = null)
 * @method GestorUsuario[]    findAll()
 * @method GestorUsuario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GestorUsuarioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GestorUsuario::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('g')
            ->where('g.something = :value')->setParameter('value', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
