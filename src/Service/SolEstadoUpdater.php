<?php

namespace App\Service;

use App\Entity\SolicitudServicio;
use App\Repository\SolicitudServicioRepository;
use App\Entity\SolicitudRequerimientos;
use App\Repository\SolicitudRequerimientosRepository;
use App\Entity\SolicitudTarea;
use App\Repository\SolicitudTareaRepository;
use Doctrine\ORM\EntityManagerInterface;

class SolEstadoUpdater{
    private $em;
    private $servRepo;
    private $reqRepo;
    private $taskRepo;

    public function __construct(
        EntityManagerInterface $em, SolicitudServicioRepository $solServRepo,
        SolicitudRequerimientosRepository $solReqRepo, SolicitudTareaRepository $solTaskRepo
    ){
        $this->em = $em;
        $this->servRepo = $solServRepo;
        $this->reqRepo = $solReqRepo;
        $this->taskRepo = $solTaskRepo;
    }

    /*
     * A Ejecutarse con la Creación de un Requerimientos.
     */
    public function aceptarServicio(SolicitudServicio $solServ){

        if ($solServ->getEstado() < 1){
            $solServ->setEstado(1);

            $this->em->flush();

            return true;
        }

        return false;
    }

    /*
     * A Ejecutarse con la Creación de una Tarea.
     * Cambia el Estado de la Solicitud de Servicio predecesora si esta última no estaba En Proceso. 
     */
    public function aceptarRequerimientos(SolicitudRequerimientos $solReq){

        if ($solReq->getEstado() < 1){
            $solReq->setEstado(1);

            $solServ = $solReq->$getServicio();
            if ($solServ->getEstado() !== 3){
                $solServ->setEstado(3);
            }
            $this->em->flush();

            return true;
        }

        return false;
    }

    /*
     * A Ejecutarse con la Finalización de un Requerimiento.
     */
    public function completarRequerimientos(SolicitudRequerimientos $solReq){
        $ufReq = $this->reqRepo->countUnfinished();

        $solReq->setEstado(7);

        if ($ufReq < 2){
            $solServ = $solReq->$getServicio();
            $solServ->setEstado(5);
        }
        $this->em->flush();

        return true;
    }

    public function cancelarRequerimientos(SolicitudRequerimientos $solReq){
        $ufReq = $this->reqRepo->countUnfinished();
        $ucReq = $this->reqRepo->countUncancelled();

        $solReq->setEstado(2);

        if ($ufReq < 2){
            $solServ = $solReq->$getServicio();
            $solServ->setEstado(2);
        }
        $this->em->flush();

        return true;
    }

    public function aceptarTarea(SolicitudTarea $solTask){

        if ($solTask->getEstado() < 1){
            $solTask->setEstado(1);

            $solReq = $solTask->$getRequerimiento();
            if ($solReq->getEstado() !== 3){
                $solReq->setEstado(3);
            }
            $this->em->flush();

            return true;
        }

        return false;
    }

    public function rechazarTarea(SolicitudTarea $solTask){

        if (($solTask->getEstado() < 1) || ($solTask->getEstado() == 3)){
            $solTask->setEstado(2);

            $this->em->flush();

            return true;
        }

        return false;
    }

    public function entregarTarea(SolicitudTarea $solTask){
        $solTask->setEstado(5);
        
        $this->em->flush();

        return true;
    }

    public function reasignarTarea(SolicitudTarea $solTask){

        if (($solTask->getEstado() < 5) && (($solTask->getEstado() % 2) == 0)){
            $solTask->setEstado(3);

            $this->em->flush();

            return true;
        }

        return false;
    } 

    public function completarTarea(SolicitudTarea $solTask){
        $ufTask = $this->taskRepo->countUnfinished();

        $solTask->setEstado(7);

        if ($ufTask < 2){
            $solReq = $solTask->$getServicio();
            $solReq->setEstado(5);
        }
        $this->em->flush();

        return true;
    }

    public function cancelarTarea(SolicitudTarea $solTask){
        $ufTask = $this->taskRepo->countUnfinished();
        $ucTask = $this->taskRepo->countUncanceled();

        $solTask->setEstado(6);

        if ($ufTask < 2){
            $solReq = $solTask->$getServicio();
            if ($ufTask === $ucTask) {
                $solReq->setEstado(6);
            } else {
                $solReq->setEstado(5);
            }
        }
        $this->em->flush();

        return true;
    }

    public function marcarTareasRetrasadas(SolicitudTarea $solTask){
        $overdueTasks = $this->taskRepo->findOverdue(new \DateTime("now"));

        foreach ($overdueTasks as $tareaRetrasada) {
            $tareaRetrasada->setEstado(4);
        }

        $this->em->flush();

        return true;

    }

    public function excusarTarea(SolicitudTarea $solTask){
        $solTask->setEstado(1);
        
        $this->em->flush();

        return true;
    }

}