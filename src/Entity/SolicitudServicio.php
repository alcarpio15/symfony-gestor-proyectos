<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Entity\SolicitudRequerimientos;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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
     * Clave Foranea al Usuario que crea la Solicitud.
     *
     * @ORM\ManyToOne(targetEntity="GestorUsuario")
     * @ORM\JoinColumn(name="servicio_autor_id", referencedColumnName="id")
     */
    private $autor;

    /**
     * @var string
     *
     * @ORM\Column(name="servicio_titulo", type="text", length=140)
     */
    private $asunto;

    /**
     * @ORM\Column(name="servicio_descrp", type="text", length=560, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(name="servicio_codigo_documento", type="text", length=64, nullable=true)
     */
    private $codigoDocumento;

    /**
     * @ORM\OneToMany(targetEntity="SolicitudRequerimientos", mappedBy="servicio")
     * @ORM\JoinColumn(name="servicio_requerimientos_id", referencedColumnName="id")
     */
    private $requerimientos;

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

    public function __construct()
    {
        $this->requerimientos = new ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return GestorUsuario
     */
    public function getAutor()
    {
        return $this->autor;
    }

    /**
     * @param GestorUsuario
     *
     * @return self
     */
    public function setAutor($autor)
    {
        $this->autor = $autor;

        return $this;
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
    public function getCodigoDocumento()
    {
        return $this->codigoDocumento;
    }

    /**
     * @param mixed $codigoDocumento
     *
     * @return self
     */
    public function setCodigoDocumento($codigoDocumento)
    {
        $this->codigoDocumento = $codigoDocumento;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequerimientos()
    {
        return $this->requerimientos;
    }

    public function addRequerimiento(SolicitudRequerimientos $requerimiento)
    {
        if (!$this->requerimientos->contains($requerimiento)) {
            $this->requerimientos[] = $requerimiento;
            $requerimiento->setServicio($this);
        }

        return $this;
    }

    public function removeRequerimiento(SolicitudRequerimientos $requerimiento): self
    {
        if ($this->requerimientos->contains($requerimiento)) {
            $this->requerimientos->removeElement($requerimiento);
            // set the owning side to null (unless already changed)
            if ($requerimiento->getServicio() === $this) {
                $requerimiento->setServicio(null);
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

    public function __toString()
    {

        $label = "(" . $this->getId() . ") " . $this->getAsunto();

        return $label;
    }
}
