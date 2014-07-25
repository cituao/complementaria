<?php

namespace Ingenieria\ProfesorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

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
 	 * @Assert\NotBlank(message="Es obligatorio!")
     */
    private $nombre;

     /**
 	 * @Assert\NotBlank(message="Es obligatorio!")
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
	 * @Assert\NotBlank(message="Es obligatorio!")
	 * @Assert\Regex(pattern="/^\d+$/", match=true, message="Dato inválido!")
     */
    private $numeroCupos;

	private $file;

	protected $profesor;

	protected $estudiantes;

	protected $categoria;
	
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
		
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

	public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/fotos';
    }


	public function upload()
	{
	 // the file property can be empty if the field is not required
		if (null === $this->getFile()) {
		    return;
		}

		// use the original file name here but you should
		// sanitize it at least to avoid any security issues

		// move takes the target directory and then the
		// target filename to move to
		
		//asignamos el codigo uao a la foto
		$nombre = $this->codigo.'.png'; 
		$this->getFile()->move(
		    $this->getUploadRootDir(),
		    $nombre
		);
		//$this->getFile()->getClientOriginalName()
		// set the path property to the filename where you've saved the file
		//$this->path = $this->getFile()->getClientOriginalName();
		$this->path = $nombre;
		// clean up the file property as you won't need it anymore
		$this->file = null;	
	}

    /**
     * Set profesor
     *
     * @param \Ingenieria\ProfesorBundle\Entity\Profesor $profesor
     * @return Actividad
     */
    public function setProfesor(\Ingenieria\ProfesorBundle\Entity\Profesor $profesor = null)
    {
        $this->profesor = $profesor;

        return $this;
    }

    /**
     * Get profesor
     *
     * @return \Ingenieria\ProfesorBundle\Entity\Profesor 
     */
    public function getProfesor()
    {
        return $this->profesor;
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
     * @return Actividad
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
     * Set categoria
     *
     * @param \Ingenieria\UsuarioBundle\Entity\Categoria $categoria
     * @return Actividad
     */
    public function setCategoria(\Ingenieria\UsuarioBundle\Entity\Categoria $categoria = null)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return \Ingenieria\UsuarioBundle\Entity\Categoria 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }
}
