<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $procedenciaDepartamento;

     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $procedenciaTelefono;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     * @Assert\Email()
     */
    private $procedenciaEmail;

    /**
     * Clave Foranea al Area al cual se va asignar la Solicitud de Requerimentos.
     *
     * @ORM\ManyToOne(targetEntity="AreaCoordinacion")
     * @ORM\JoinColumn(name="requerimientos_area_id", referencedColumnName="id")
     */
    private $area;

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
