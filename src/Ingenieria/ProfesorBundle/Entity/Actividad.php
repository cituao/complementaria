<?php

namespace Ingenieria\ProfesorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Actividad
 */
class Actividad
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $descripcion;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $imagen;

    /**
     * @var integer
     */
    private $categoria;

    /**
     * @var integer
     */
    private $numeroCupos;


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
     * @return Actividad
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Actividad
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
     * Set url
     *
     * @param string $url
     * @return Actividad
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set imagen
     *
     * @param string $imagen
     * @return Actividad
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get imagen
     *
     * @return string 
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * Set categoria
     *
     * @param integer $categoria
     * @return Actividad
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return integer 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set numeroCupos
     *
     * @param integer $numeroCupos
     * @return Actividad
     */
    public function setNumeroCupos($numeroCupos)
    {
        $this->numeroCupos = $numeroCupos;

        return $this;
    }

    /**
     * Get numeroCupos
     *
     * @return integer 
     */
    public function getNumeroCupos()
    {
        return $this->numeroCupos;
    }
}
