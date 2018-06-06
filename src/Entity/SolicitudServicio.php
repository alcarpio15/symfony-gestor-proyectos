<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="servicios_solicitudes")
 * @ORM\Entity(repositoryClass="App\Repository\SolicitudServicioRepository")
 */
class SolicitudServicio
{
    /**
     * @var integer
     * 
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    // add your own fields
    /**
     * @var string
     *
     * @ORM\Column(name="servicio_titulo", type="text", length=140)
     */
    private $asunto;

    /**
     * @ORM\Column(name="servicio_descrp", type="text", length=560)
     */
    private $descripcion;

    /**
     * @ORM\Column(name="servicio_estado", type="integer")
     */
    private $estado;

    /**
     * @ORM\Column(name="servicio_creado", type="datetimetz")
     */
    private $creado;

    /**
     * @ORM\Column(name="servicio_modificado", type="datetimetz")
     */
    private $modificado;

    /**
     * ORM\Column()
     */
    //private $var;


    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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

        return $this;
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

        return $this;
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

        return $this;
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
