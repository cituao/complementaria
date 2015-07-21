<?php

namespace Ingenieria\DirectorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\File(maxSize="6000000")
     */
	private $file;
    
	private $path;

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

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
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
     * Set path
     *
     * @param string $path
     * @return Document
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
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
        return 'uploads/grupos';
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
		$nombre = $this->path.'.csv'; 
		$this->getFile()->move(
		    $this->getUploadRootDir(),
		    $nombre
		);
		//$this->getFile()->getClientOriginalName()
		// set the path property to the filename where you've saved the file
		//$this->path = $this->getFile()->getClientOriginalName();
		//$this->path = $nombre;
		// clean up the file property as you won't need it anymore
		$this->file = null;	
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
     * @return Grupo
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
