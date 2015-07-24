<?php

namespace Ingenieria\ProfesorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Profesor
 */
class Profesor
{
    /**
     * @var integer
     */
    private $id;

     /**
 	 * @Assert\NotBlank(message="Es obligatorio!")
	 * @Assert\Regex(pattern="/\d/", match=false, message="Nombre inválido!")
     */
    private $nombres;

     /**
 	 * @Assert\NotBlank(message="Es obligatorio!")
	 * @Assert\Regex(pattern="/\d/", match=false, message="Apellido inválido!")
     */
    private $apellidos;

     /**
	 * @Assert\NotBlank(message="Es obligatorio!")
	 * @Assert\Regex(pattern="/^\d+$/", match=true, message="Cédula inválida!")
     */
    private $ci;

	/**
	 * @Assert\NotBlank(message="Es obligatorio!")
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     */
    private $email;

	/**
	 * @Assert\NotBlank(message="Es obligatorio!")
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     */
    private $emailInstitucional;

	protected $grupo;
    
	/**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombres
     *
     * @param string $nombres
     * @return Profesor
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;

        return $this;
    }

    /**
     * Get nombres
     *
     * @return string 
     */
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * Set apellidos
     *
     * @param string $apellidos
     * @return Profesor
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string 
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set ci
     *
     * @param string $ci
     * @return Profesor
     */
    public function setCi($ci)
    {
        $this->ci = $ci;

        return $this;
    }

    /**
     * Get ci
     *
     * @return string 
     */
    public function getCi()
    {
        return $this->ci;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Profesor
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set emailInstitucional
     *
     * @param string $emailInstitucional
     * @return Profesor
     */
    public function setEmailInstitucional($emailInstitucional)
    {
        $this->emailInstitucional = $emailInstitucional;

        return $this;
    }

    /**
     * Get emailInstitucional
     *
     * @return string 
     */
    public function getEmailInstitucional()
    {
        return $this->emailInstitucional;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
 
	}

	/**
	 *
	 * nombre completo para los select 
	 */
	public function getNombreCompleto(){

		return sprintf('%s %s',$this->nombres, $this->apellidos);
	}

    /**
     * Set grupo
     *
     * @param \Ingenieria\DirectorBundle\Entity\Grupo $grupo
     * @return Profesor
     */
    public function setGrupo(\Ingenieria\DirectorBundle\Entity\Grupo $grupo = null)
    {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * Get grupo
     *
     * @return \Ingenieria\DirectorBundle\Entity\Grupo 
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

	public function __toString()
	{
		$nombrecompleto = $this->nombres." ".$this->apellidos;	
		return $nombrecompleto;
	}
}
