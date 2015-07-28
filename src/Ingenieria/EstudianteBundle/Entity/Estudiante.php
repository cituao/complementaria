<?php

namespace Ingenieria\EstudianteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Estudiante
 */
class Estudiante
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $codigo;

    /**
     * @var string
     */
    private $ci;

    /**
     * @var string
     */
    private $nombres;

    /**
     * @var string
     */
    private $apellidos;

    /**
     * @var string
     */
    private $email;	

    /**
     * @var boolean
     */
    private $aprobadoCronograma;

    /**
     * @var boolean
     */
    private $rechazadoCronograma;

    /**
     * @var string
     */
    private $emailInstitucional;

	protected $actividades;

	protected $grupo;
	
	protected $actividad;
	
	protected $bitacora;

	protected $subgrupo;

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
     * Set codigo
     *
     * @param string $codigo
     * @return Estudiante
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set ci
     *
     * @param string $ci
     * @return Estudiante
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
     * Set nombres
     *
     * @param string $nombres
     * @return Estudiante
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
     * @return Estudiante
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
     * Set email
     *
     * @param string $email
     * @return Estudiante
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
     * @return Estudiante
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
     * Set actividad
     *
     * @param \Ingenieria\ProfesorBundle\Entity\Actividad $actividad
     * @return Estudiante
     */
    public function setActividad(\Ingenieria\ProfesorBundle\Entity\Actividad $actividad = null)
    {
        $this->actividad = $actividad;

        return $this;
    }

    /**
     * Get actividad
     *
     * @return \Ingenieria\ProfesorBundle\Entity\Actividad 
     */
    public function getActividad()
    {
        return $this->actividad;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->actividades = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add actividades
     *
     * @param \Ingenieria\EstudianteBundle\Entity\Cronograma $actividades
     * @return Estudiante
     */
    public function addActividade(\Ingenieria\EstudianteBundle\Entity\Cronograma $actividades)
    {
        $this->actividades[] = $actividades;

        return $this;
    }

    /**
     * Remove actividades
     *
     * @param \Ingenieria\EstudianteBundle\Entity\Cronograma $actividades
     */
    public function removeActividade(\Ingenieria\EstudianteBundle\Entity\Cronograma $actividades)
    {
        $this->actividades->removeElement($actividades);
    }

    /**
     * Get actividades
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActividades()
    {
        return $this->actividades;
    }

    /**
     * Set aprobadoCronograma
     *
     * @param boolean $aprobadoCronograma
     * @return Estudiante
     */
    public function setAprobadoCronograma($aprobadoCronograma)
    {
        $this->aprobadoCronograma = $aprobadoCronograma;

        return $this;
    }

    /**
     * Get aprobadoCronograma
     *
     * @return boolean 
     */
    public function getAprobadoCronograma()
    {
        return $this->aprobadoCronograma;
    }

    /**
     * Set rechazadoCronograma
     *
     * @param boolean $rechazadoCronograma
     * @return Estudiante
     */
    public function setRechazadoCronograma($rechazadoCronograma)
    {
        $this->rechazadoCronograma = $rechazadoCronograma;

        return $this;
    }

    /**
     * Get rechazadoCronograma
     *
     * @return boolean 
     */
    public function getRechazadoCronograma()
    {
        return $this->rechazadoCronograma;
    }

    /**
     * Set grupo
     *
     * @param \Ingenieria\DirectorBundle\Entity\Grupo $grupo
     * @return Estudiante
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

    /**
     * Add bitacora
     *
     * @param \Ingenieria\EstudianteBundle\Entity\Bitacora $bitacora
     * @return Estudiante
     */
    public function addBitacora(\Ingenieria\EstudianteBundle\Entity\Bitacora $bitacora)
    {
        $this->bitacora[] = $bitacora;

        return $this;
    }

    /**
     * Remove bitacora
     *
     * @param \Ingenieria\EstudianteBundle\Entity\Bitacora $bitacora
     */
    public function removeBitacora(\Ingenieria\EstudianteBundle\Entity\Bitacora $bitacora)
    {
        $this->bitacora->removeElement($bitacora);
    }

    /**
     * Get bitacora
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBitacora()
    {
        return $this->bitacora;
    }

    /**
     * Set subgrupo
     *
     * @param \Ingenieria\ProfesorBundle\Entity\Subgrupo $subgrupo
     * @return Estudiante
     */
    public function setSubgrupo(\Ingenieria\ProfesorBundle\Entity\Subgrupo $subgrupo = null)
    {
        $this->subgrupo = $subgrupo;

        return $this;
    }

    /**
     * Get subgrupo
     *
     * @return \Ingenieria\ProfesorBundle\Entity\Subgrupo 
     */
    public function getSubgrupo()
    {
        return $this->subgrupo;
    }
}
