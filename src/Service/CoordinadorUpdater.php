<?php

namespace App\Service;

use App\Entity\AreaCoordinacion;
use App\Repository\AreaCoordinacionRepository;
use App\Entity\GestorUsuario;
use App\Repository\GestorUsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;

class CoordinadorUpdater{
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    public function getCoordinadores(AreaCoordinacionRepository $areaCoordRepo)
    {
        $areas = $areaCoordRepo->findAll();
        $coordinadores = array();

        foreach ($areas as $area) {
            $coordinadores[] = $area->getCoordinador();
        }

        return array_unique($coordinadores);
    }

    public function updateRoles(array $prevCoord, array $currCoord)
    {
        $role = array('ROLE_CORDAR');

        $rmvCoord = array_merge(array_udiff($prevCoord, $currCoord,
            function(GestorUsuario $user_a, GestorUsuario $user_b){
                if ($user_a->getId() == $user_b->getId()) {
                    return 0;
                } elseif ($user_a->getId() > $user_b->getId()) {
                    return 1;
                } else {
                    return -1;
                }
                
            })
        );
        $addCoord = array_merge(array_udiff($currCoord, $prevCoord,
            function(GestorUsuario $user_a, GestorUsuario $user_b){
                if ($user_a->getId() == $user_b->getId()) {
                    return 0;
                } elseif ($user_a->getId() > $user_b->getId()) {
                    return 1;
                } else {
                    return -1;
                }
                
            })
        );

        foreach ($addCoord as $add) {
            $add->addRoles($role);
        }
        foreach ($rmvCoord as $rmv) {
            $rmv->removeRoles($role);
        }

        $this->em->flush();

        return true;
    }
}