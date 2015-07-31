<?php

namespace Ingenieria\ProfesorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subgrupo
 */
class Subgrupo
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nombre;

	protected $grupo;

	protected $estudiantes;
	
	protected $lider;

	protected $actividad;
	
	protected $encuentros;

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
     * Set nombre
     *
     * @param string $nombre
     * @return Subgrupo
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set grupo
     *
     * @param \Ingenieria\DirectorBundle\Entity\Grupo $grupo
     * @return Subgrupo
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
     * Constructor
     */
    public function __construct()
    {
        $this->estudiantes = new \Doctrine\Common\Collections\ArrayCollection();
    }

	public function __toString()
	{
		$nombrecompleto = $this->nombre;	
		return $nombrecompleto;
	}
	
    /**
     * Add estudiantes
     *
     * @param \Ingenieria\EstudianteBundle\Entity\Estudiante $estudiantes
     * @return Subgrupo
     */
    public function addEstudiante(\Ingenieria\EstudianteBundle\Entity\Estudiante $estudiantes)
    {
        $this->estudiantes[] = $estudiantes;

        return $this;
    }

    /**
     * Remove estudiantes
     *
     * @param \Ingenieria\EstudianteBundle\Entity\Estudiante $estudiantes
     */
    public function removeEstudiante(\Ingenieria\EstudianteBundle\Entity\Estudiante $estudiantes)
    {
        $this->estudiantes->removeElement($estudiantes);
    }

    /**
     * Get estudiantes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstudiantes()
    {
        return $this->estudiantes;
    }

    /**
     * Set lider
     *
     * @param \Ingenieria\EstudianteBundle\Entity\Estudiante $lider
     * @return Subgrupo
     */
    public function setLider(\Ingenieria\EstudianteBundle\Entity\Estudiante $lider = null)
    {
        $this->lider = $lider;

        return $this;
    }

    /**
     * Get lider
     *
     * @return \Ingenieria\EstudianteBundle\Entity\Estudiante 
     */
    public function getLider()
    {
        return $this->lider;
    }

    /**
     * Set actividad
     *
     * @param \Ingenieria\ProfesorBundle\Entity\Actividad $actividad
     * @return Subgrupo
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
     * Add encuentros
     *
     * @param \Ingenieria\ProfesorBundle\Entity\Encuentro $encuentros
     * @return Subgrupo
     */
    public function addEncuentro(\Ingenieria\ProfesorBundle\Entity\Encuentro $encuentros)
    {
        $this->encuentros[] = $encuentros;

        return $this;
    }

    /**
     * Remove encuentros
     *
     * @param \Ingenieria\ProfesorBundle\Entity\Encuentro $encuentros
     */
    public function removeEncuentro(\Ingenieria\ProfesorBundle\Entity\Encuentro $encuentros)
    {
        $this->encuentros->removeElement($encuentros);
    }

    /**
     * Get encuentros
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEncuentros()
    {
        return $this->encuentros;
    }
}
