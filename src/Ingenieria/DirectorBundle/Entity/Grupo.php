<?php

namespace Ingenieria\DirectorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Grupo
 */
class Grupo
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nombre;


	protected $tutor;

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
     * @return Grupo
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
     * Set tutor
     *
     * @param \Ingenieria\ProfesorBundle\Entity\Profesor $tutor
     * @return Grupo
     */
    public function setTutor(\Ingenieria\ProfesorBundle\Entity\Profesor $tutor = null)
    {
        $this->tutor = $tutor;

        return $this;
    }

    /**
     * Get tutor
     *
     * @return \Ingenieria\ProfesorBundle\Entity\Profesor 
     */
    public function getTutor()
    {
        return $this->tutor;
    }
}
