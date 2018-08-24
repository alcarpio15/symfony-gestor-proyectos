<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AreaCoordinacionRepository")
 */
class AreaCoordinacion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    // add your own fields
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $area;

    /**
     * Clave Foranea al Coordinador del Area.
     *
     * @ORM\ManyToOne(targetEntity="GestorUsuario")
     * @ORM\JoinColumn(name="area_coordinador_id", referencedColumnName="id")
     */
    private $coordinador;

    

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @return GestorUsuario
     */
    public function getCoordinador()
    {
        return $this->coordinador;
    }

    /**
     * @param string $area
     *
     * @return self
     */
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * @param mixed $coordinador
     *
     * @return self
     */
    public function setCoordinador($coordinador)
    {
        $this->coordinador = $coordinador;

        return $this;
    }
}
