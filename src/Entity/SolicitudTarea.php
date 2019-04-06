<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Entity\SolicitudTarea;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="tarea_solicitudes")
 * @ORM\Entity(repositoryClass="App\Repository\SolicitudTareaRepository")
 */
class SolicitudTarea
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    // add your own fields
    /**
     * Clave Foranea a la Solicitud de Servicio asociada a esta.
     *
     * @ORM\ManyToOne(targetEntity="SolicitudRequerimientos", inversedBy="tareas")
     * @ORM\JoinColumn(name="tarea_requerimiento_id", referencedColumnName="id")
     */
    private $requerimiento;

    /**
     * Clave Foranea al Desarrollador.
     *
     * @ORM\ManyToOne(targetEntity="GestorUsuario")
     * @ORM\JoinColumn(name="tarea_desarrollador_id", referencedColumnName="id")
     */
    private $desarrollador;

    /**
     * @var string
     *
     * @ORM\Column(name="tarea_titulo", type="text", length=140)
     */
    private $asunto;

    /**
     * @ORM\Column(name="tarea_descrp", type="text", length=560, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(name="tarea_entrega_estimada", type="datetimetz")
     */
    private $fechaEntregaEstimada;

     /**
     * @ORM\OneToMany(targetEntity="HistorialTarea", mappedBy="tarea", cascade={"persist"})
     * @ORM\JoinColumn(name="tarea_historial_id", referencedColumnName="id")
     */
    private $historial;

    /**
     * @ORM\Column(name="tarea_estado", type="integer")
     */
    private $estado;

    /**
     * @ORM\Column(name="tarea_creado", type="datetimetz")
     */
    private $creado;

    /**
     * @ORM\Column(name="tarea_modificado", type="datetimetz")
     */
    private $modificado;


    /**
     * @return self
     */
    public function __construct()
    {
        $this->historial = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return SolicitudRequerimientos
     */
    public function getRequerimiento()
    {
        return $this->requerimiento;
    }

    /**
     * @param SolicitudRequerimientos $requerimiento
     *
     * @return self
     */
    public function setRequerimiento($requerimiento)
    {
        $this->requerimiento = $requerimiento;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDesarrollador()
    {
        return $this->desarrollador;
    }

    /**
     * @param mixed $desarrollador
     *
     * @return self
     */
    public function setDesarrollador($desarrollador)
    {
        $this->desarrollador = $desarrollador;

        return $this;
    }

    /**
     * @return string
     */
    public function getAsunto()
    {
        return $this->asunto;
    }

    /**
     * @param string $asunto
     *
     * @return self
     */
    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param string $descripcion
     *
     * @return self
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * @return \Datetime
     */
    public function getFechaEntregaEstimada()
    {
        return $this->fechaEntregaEstimada;
    }

    /**
     * @param \DateTime $fechaEntregaEstimada
     *
     * @return self
     */
    public function setFechaEntregaEstimada($fechaEntregaEstimada)
    {
        $this->fechaEntregaEstimada = $fechaEntregaEstimada;
    }

    /**
     * @return mixed
     */
    public function getHistorial()
    {
        return $this->historial;
    }

    public function addHistorial(HistorialTarea $entradaHistorial)
    {
        if (!$this->historial->contains($entradaHistorial)) {
            $this->historial[] = $entradaHistorial;
            $entradaHistorial->setTarea($this);
        }

        return $this;
    }

    public function removeHistorial(HistorialTarea $entradaHistorial): self
    {
        if ($this->historial->contains($entradaHistorial)) {
            $this->historial->removeElement($entradaHistorial);
            // set the owning side to null (unless already changed)
            if ($entradaHistorial->getTarea() === $this) {
                $entradaHistorial->setTarea(null);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param integer $estado
     *
     * @return self
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * @return \DateTime
     */
    public function getCreado()
    {
        return $this->creado;
    }

    /**
     * @param \DateTime $creado
     *
     * @return self
     */
    public function setCreado($creado)
    {
        $this->creado = $creado;
    }

    /**
     * @return \DateTime
     */
    public function getModificado()
    {
        return $this->modificado;
    }

    /**
     * @param \DateTime $modificado
     *
     * @return self
     */
    public function setModificado($modificado)
    {
        $this->modificado = $modificado;
    }

    /**
     * Para ejecutarse antes de la inserción en la Base de Datos 
     *
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->estado = 0;
        $this->creado = new \DateTime("now");
        $this->modificado = $this->creado;
    }

    /**
     * Para ejecutarse con modificaciones de otros campos
     * 
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->modificado = new \DateTime("now");
    }

}
