<?php

namespace Ingenieria\EstudianteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bitacora
 */
class Bitacora
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nombreActividad;

    /**
     * @var string
     */
    private $descripcion;

    /**
     * @var \DateTime
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     */
    private $fechaFin;

    /**
     * @var boolean
     */
    private $finalizada;

    /**
     * @var boolean
     */
    private $verificado;

	protected $estudiante;
	

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
     * Set nombreActividad
     *
     * @param string $nombreActividad
     * @return Bitacora
     */
    public function setNombreActividad($nombreActividad)
    {
        $this->nombreActividad = $nombreActividad;

        return $this;
    }

    /**
     * Get nombreActividad
     *
     * @return string 
     */
    public function getNombreActividad()
    {
        return $this->nombreActividad;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Bitacora
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return Bitacora
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime 
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     * @return Bitacora
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime 
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set finalizada
     *
     * @param boolean $finalizada
     * @return Bitacora
     */
    public function setFinalizada($finalizada)
    {
        $this->finalizada = $finalizada;

        return $this;
    }

    /**
     * Get finalizada
     *
     * @return boolean 
     */
    public function getFinalizada()
    {
        return $this->finalizada;
    }

    /**
     * Set verificado
     *
     * @param boolean $verificado
     * @return Bitacora
     */
    public function setVerificado($verificado)
    {
        $this->verificado = $verificado;

        return $this;
    }

    /**
     * Get verificado
     *
     * @return boolean 
     */
    public function getVerificado()
    {
        return $this->verificado;
    }

    /**
     * Set estudiante
     *
     * @param \Ingenieria\EstudianteBundle\Entity\Estudiante $estudiante
     * @return Bitacora
     */
    public function setEstudiante(\Ingenieria\EstudianteBundle\Entity\Estudiante $estudiante = null)
    {
        $this->estudiante = $estudiante;

        return $this;
    }

    /**
     * Get estudiante
     *
     * @return \Ingenieria\EstudianteBundle\Entity\Estudiante 
     */
    public function getEstudiante()
    {
        return $this->estudiante;
    }
}
