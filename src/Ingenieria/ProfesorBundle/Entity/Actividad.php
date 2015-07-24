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

	protected $estudiantes;

	protected $categoria;

	private $mentor;

	private $email;

	private $proposito;

	private $dirigida;

	private $trabajo;

	private $aprendizaje;

	private $pensamiento;

	private $autonomia;

	private $integralidad;

	private $excelencia;

	private $creatividad;

	private $eticidad;

	private $responsabilidad;

	private $pertenencia;
	
	private $honestidad;

	private $descripcion;

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

    /**
     * Set mentor
     *
     * @param string $mentor
     * @return Actividad
     */
    public function setMentor($mentor)
    {
        $this->mentor = $mentor;

        return $this;
    }

    /**
     * Get mentor
     *
     * @return string 
     */
    public function getMentor()
    {
        return $this->mentor;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Actividad
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
     * Set proposito
     *
     * @param string $proposito
     * @return Actividad
     */
    public function setProposito($proposito)
    {
        $this->proposito = $proposito;

        return $this;
    }

    /**
     * Get proposito
     *
     * @return string 
     */
    public function getProposito()
    {
        return $this->proposito;
    }

    /**
     * Set dirigida
     *
     * @param string $dirigida
     * @return Actividad
     */
    public function setDirigida($dirigida)
    {
        $this->dirigida = $dirigida;

        return $this;
    }

    /**
     * Get dirigida
     *
     * @return string 
     */
    public function getDirigida()
    {
        return $this->dirigida;
    }

    /**
     * Set trabajo
     *
     * @param boolean $trabajo
     * @return Actividad
     */
    public function setTrabajo($trabajo)
    {
        $this->trabajo = $trabajo;

        return $this;
    }

    /**
     * Get trabajo
     *
     * @return boolean 
     */
    public function getTrabajo()
    {
        return $this->trabajo;
    }

    /**
     * Set aprendizaje
     *
     * @param boolean $aprendizaje
     * @return Actividad
     */
    public function setAprendizaje($aprendizaje)
    {
        $this->aprendizaje = $aprendizaje;

        return $this;
    }

    /**
     * Get aprendizaje
     *
     * @return boolean 
     */
    public function getAprendizaje()
    {
        return $this->aprendizaje;
    }

    /**
     * Set pensamiento
     *
     * @param boolean $pensamiento
     * @return Actividad
     */
    public function setPensamiento($pensamiento)
    {
        $this->pensamiento = $pensamiento;

        return $this;
    }

    /**
     * Get pensamiento
     *
     * @return boolean 
     */
    public function getPensamiento()
    {
        return $this->pensamiento;
    }

    /**
     * Set autonomia
     *
     * @param boolean $autonomia
     * @return Actividad
     */
    public function setAutonomia($autonomia)
    {
        $this->autonomia = $autonomia;

        return $this;
    }

    /**
     * Get autonomia
     *
     * @return boolean 
     */
    public function getAutonomia()
    {
        return $this->autonomia;
    }

    /**
     * Set integralidad
     *
     * @param boolean $integralidad
     * @return Actividad
     */
    public function setIntegralidad($integralidad)
    {
        $this->integralidad = $integralidad;

        return $this;
    }

    /**
     * Get integralidad
     *
     * @return boolean 
     */
    public function getIntegralidad()
    {
        return $this->integralidad;
    }

    /**
     * Set excelencia
     *
     * @param boolean $excelencia
     * @return Actividad
     */
    public function setExcelencia($excelencia)
    {
        $this->excelencia = $excelencia;

        return $this;
    }

    /**
     * Get excelencia
     *
     * @return boolean 
     */
    public function getExcelencia()
    {
        return $this->excelencia;
    }

    /**
     * Set creatividad
     *
     * @param boolean $creatividad
     * @return Actividad
     */
    public function setCreatividad($creatividad)
    {
        $this->creatividad = $creatividad;

        return $this;
    }

    /**
     * Get creatividad
     *
     * @return boolean 
     */
    public function getCreatividad()
    {
        return $this->creatividad;
    }

    /**
     * Set eticidad
     *
     * @param boolean $eticidad
     * @return Actividad
     */
    public function setEticidad($eticidad)
    {
        $this->eticidad = $eticidad;

        return $this;
    }

    /**
     * Get eticidad
     *
     * @return boolean 
     */
    public function getEticidad()
    {
        return $this->eticidad;
    }

    /**
     * Set responsabilidad
     *
     * @param boolean $responsabilidad
     * @return Actividad
     */
    public function setResponsabilidad($responsabilidad)
    {
        $this->responsabilidad = $responsabilidad;

        return $this;
    }

    /**
     * Get responsabilidad
     *
     * @return boolean 
     */
    public function getResponsabilidad()
    {
        return $this->responsabilidad;
    }

    /**
     * Set pertenencia
     *
     * @param boolean $pertenencia
     * @return Actividad
     */
    public function setPertenencia($pertenencia)
    {
        $this->pertenencia = $pertenencia;

        return $this;
    }

    /**
     * Get pertenencia
     *
     * @return boolean 
     */
    public function getPertenencia()
    {
        return $this->pertenencia;
    }

    /**
     * Set honestidad
     *
     * @param boolean $honestidad
     * @return Actividad
     */
    public function setHonestidad($honestidad)
    {
        $this->honestidad = $honestidad;

        return $this;
    }

    /**
     * Get honestidad
     *
     * @return boolean 
     */
    public function getHonestidad()
    {
        return $this->honestidad;
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
}
