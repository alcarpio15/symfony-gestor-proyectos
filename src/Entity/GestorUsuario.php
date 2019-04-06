<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="gestor_usuarios")
 * @ORM\Entity(repositoryClass="App\Repository\GestorUsuarioRepository")
 * @UniqueEntity(fields="email", message="El Correo ya esta registrado")
 * @UniqueEntity(fields="username", message="El Nombre de Usuario ya esta registrado")
 */
class GestorUsuario implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    // add your own fields

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\Length(max=16)
     * 
     * La Longitud puede ser hasta 4096 caracteres,
     * pero es mejor trabajar con una longitud mas
     * sensato para un formulario de registro.
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * Clave Foranea al Area que pertenece como desarrollador.
     *
     * @ORM\ManyToOne(targetEntity="AreaCoordinacion")
     * @ORM\JoinColumn(name="requerimientos_area_id", referencedColumnName="id", nullable=true)
     */
    private $areaDesarrollo;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    public function __construct() {
        $this->roles = array('ROLE_USER');
        $this->activo = true;
    }

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nombres;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $apellidos;

    // other properties and methods

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getAreaDesarrollo()
    {
        return $this->areaDesarrollo;
    }

    public function setAreaDesarrollo($area_desarrollo)
    {
        $this->areaDesarrollo = $area_desarrollo;
    }

    public function getSalt()
    {
        // The bcrypt and argon2i algorithms don't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function addRoles(array $roles)
    {
        $this->roles = array_unique(array_merge($this->roles, $roles));
    }

    public function removeRoles(array $roles)
    {
        $this->roles = array_merge(array_diff($this->roles, $roles));
    }

     /**
     * @return boolean
     */
    public function getActivo()
    {
        return $this->activo;
    }

    public function activar(){
        $this->activo = true;
    }

    public function desactivar(){
        $this->activo = false;
    }
    
    public function getNombres()
    {
        return $this->nombres;
    }

    public function setNombres($nombres)
    {
        $this->nombres = $nombres;
    }

    public function getApellidos()
    {
        return $this->apellidos;
    }

    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }    

    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
        ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function __toString()
    {
        return $this->getUsername();
    }

}