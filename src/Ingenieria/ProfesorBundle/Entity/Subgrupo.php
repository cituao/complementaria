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
}
