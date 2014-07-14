<?php

namespace Ingenieria\EstudianteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cronograma
 */
class Cronograma
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
     * @var \DateTime
     */
    private $fechaEntrega;

    /**
     * @var boolean
     */
    private $estado;

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
     * @return Cronograma
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
     * Set fechaEntrega
     *
     * @param \DateTime $fechaEntrega
     * @return Cronograma
     */
    public function setFechaEntrega($fechaEntrega)
    {
        $this->fechaEntrega = $fechaEntrega;

        return $this;
    }

    /**
     * Get fechaEntrega
     *
     * @return \DateTime 
     */
    public function getFechaEntrega()
    {
        return $this->fechaEntrega;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     * @return Cronograma
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return boolean 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set estudiante
     *
     * @param \Ingenieria\EstudianteBundle\Entity\Estudiante $estudiante
     * @return Cronograma
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
