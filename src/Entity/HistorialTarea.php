<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="historial_tarea_entradas")
 * @ORM\Entity(repositoryClass="App\Repository\HistorialTareaRepository")
 */
class HistorialTarea
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Clave Foranea a la Solicitud de Tarea asociada a esta.
     *
     * @ORM\ManyToOne(targetEntity="SolicitudTarea", inversedBy="historial")
     * @ORM\JoinColumn(name="historial_tarea_id", referencedColumnName="id")
     */
    private $tarea;

    /**
     * Clave Foranea al Usuario involucrado en la formación de la Entrada.
     *
     * @ORM\ManyToOne(targetEntity="GestorUsuario")
     * @ORM\JoinColumn(name="historial_sujeto_id", referencedColumnName="id")
     */
    private $sujeto;

    /**
     * @ORM\Column(name="tarea_descrp", type="text", length=560, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(name="tarea_estado", type="integer")
     */
    private $estado;

    /**
     * @ORM\Column(name="tarea_creado", type="datetimetz")
     */
    private $creado;

    /**
     * Para ejecutarse antes de la inserción en la Base de Datos 
     *
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->creado = new \DateTime("now");
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCreado()
    {
        return $this->creado;
    }

    /**
     * @return mixed
     */
    public function getTarea()
    {
        return $this->tarea;
    }

    /**
     * @param mixed $tarea
     *
     * @return self
     */
    public function setTarea($tarea)
    {
        $this->tarea = $tarea;

        return $this;
    }

     /**
     * @return mixed
     */
    public function getSujeto()
    {
        return $this->sujeto;
    }

    /**
     * @param mixed $tarea
     *
     * @return self
     */
    public function setSujeto($sujeto)
    {
        $this->sujeto = $sujeto;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     *
     * @return self
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param mixed $estado
     *
     * @return self
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }
}
