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
 * @ORM\Table(name="requerimientos_solicitudes")
 * @ORM\Entity(repositoryClass="App\Repository\SolicitudRequerimientosRepository")
 */
class SolicitudRequerimientos
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    //add your own fields
    /**
     * Clave Foranea a la Solicitud de Servicio asociada a esta.
     *
     * @ORM\ManyToOne(targetEntity="SolicitudServicio", inversedBy="requerimientos")
     * @ORM\JoinColumn(name="requerimientos_servicio_id", referencedColumnName="id")
     */
    private $servicio;

    /**
     * @ORM\Column(name="requerimientos_procedencia_departamento", type="string", length=255, nullable=true)
     */
    private $procedenciaDepartamento;

     /**
     * @ORM\Column(name="requerimientos_procedencia_telefono", type="string", length=255, nullable=true)
     */
    private $procedenciaTelefono;

    /**
     * @ORM\Column(name="requerimientos_procedencia_email", type="string", length=255, unique=false, nullable=true)
     * @Assert\Email()
     */
    private $procedenciaEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="requerimientos_titulo", type="text", length=140)
     */
    private $asunto;

    /**
     * @ORM\Column(name="requerimientos_descrp", type="text", length=560, nullable=true)
     */
    private $descripcion;

    /**
     * Clave Foranea al Area al cual se va asignar la Solicitud de Requerimentos.
     *
     * @ORM\ManyToOne(targetEntity="AreaCoordinacion")
     * @ORM\JoinColumn(name="requerimientos_area_id", referencedColumnName="id")
     */
    private $area;

    /**
     * @ORM\OneToMany(targetEntity="SolicitudTarea", mappedBy="requerimiento")
     * @ORM\JoinColumn(name="requerimientos_tareas_id", referencedColumnName="id")
     */
    private $tareas;

    /**
     * @ORM\Column(name="requerimientos_estado", type="integer")
     */
    private $estado;

    /**
     * @ORM\Column(name="requerimientos_creado", type="datetimetz")
     */
    private $creado;

    /**
     * @ORM\Column(name="requerimientos_modificado", type="datetimetz")
     */
    private $modificado;

    /**
     * @return self
     */
    public function __construct()
    {
        $this->tareas = new ArrayCollection();
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
    public function getServicio()
    {
        return $this->servicio;
    }

    /**
     * @param mixed $servicio
     *
     * @return self
     */
    public function setServicio($servicio)
    {
        $this->servicio = $servicio;
    }

    /**
     * @return mixed
     */
    public function getProcedenciaDepartamento()
    {
        return $this->procedenciaDepartamento;
    }

    /**
     * @param mixed $procedenciaDepartamento
     *
     * @return self
     */
    public function setProcedenciaDepartamento($procedenciaDepartamento)
    {
        $this->procedenciaDepartamento = $procedenciaDepartamento;
    }

    /**
     * @return mixed
     */
    public function getProcedenciaTelefono()
    {
        return $this->procedenciaTelefono;
    }

    /**
     * @param mixed $procedenciaTelefono
     *
     * @return self
     */
    public function setProcedenciaTelefono($procedenciaTelefono)
    {
        $this->procedenciaTelefono = $procedenciaTelefono;
    }

    /**
     * @return mixed
     */
    public function getProcedenciaEmail()
    {
        return $this->procedenciaEmail;
    }

    /**
     * @param mixed $procedenciaEmail
     *
     * @return self
     */
    public function setProcedenciaEmail($procedenciaEmail)
    {
        $this->procedenciaEmail = $procedenciaEmail;
    }

    /**
     * @return mixed
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
     * @return mixed
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @param mixed $area
     *
     * @return self
     */
    public function setArea($area)
    {
        $this->area = $area;
    }

    /**
     * @return mixed
     */
    public function getTareas()
    {
        return $this->tareas;
    }

    public function addTarea(SolicitudTarea $tarea)
    {
        if (!$this->tareas->contains($tarea)) {
            $this->tareas[] = $tarea;
            $tarea->setServicio($this);
        }

        return $this;
    }

    public function removeTarea(SolicitudTarea $tarea): self
    {
        if ($this->tareas->contains($tarea)) {
            $this->tareas->removeElement($tarea);
            // set the owning side to null (unless already changed)
            if ($tarea->getServicio() === $this) {
                $tarea->setServicio(null);
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
