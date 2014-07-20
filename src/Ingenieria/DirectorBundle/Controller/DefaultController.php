<?php

namespace Ingenieria\DirectorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ingenieria\ProfesorBundle\Entity\Profesor;
use Ingenieria\UsuarioBundle\Entity\Usuario;
use Ingenieria\DirectorBundle\Form\Type\ProfesorType;
use Ingenieria\DirectorBundle\Form\Type\ActividadType;

class DefaultController extends Controller
{
    public function indexAction()
    {
   		$repository = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Profesor');
		$listaProfesores = $repository->findAll();
		
		if (!$listaProfesores) {
			$msgerr = array('descripcion'=>'No hay profesores registrados!','id'=>'1');
		}else{
			$msgerr = array('descripcion'=>'','id'=>'0');
		}
		/*
				//buscamos el programa
		$user = $this->get('security.context')->getToken()->getUser();
		$coordinador =  $user->getUsername();
		$repository = $this->getDoctrine()->getRepository('CituaoUsuarioBundle:Programa');
		$programa = $repository->findOneByCoordinador($coordinador);
		*/
		
		return $this->render('IngenieriaDirectorBundle:Default:profesores.html.twig', array('listaProfesores' => $listaProfesores, 'msgerr' => $msgerr));
    }
	
	//********************************************************
	// Muestra un listado de profesores
	//******************************************************** 	
	public function profesoresAction(){
		$repository = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Profesor');
		$listaProfesores = $repository->findAll();
		
		if (!$listaProfesores) {
			$msgerr = array('descripcion'=>'No hay profesores registrados!','id'=>'1');
		}else{
			$msgerr = array('descripcion'=>'','id'=>'0');
		}
		/*
				//buscamos el programa
		$user = $this->get('security.context')->getToken()->getUser();
		$coordinador =  $user->getUsername();
		$repository = $this->getDoctrine()->getRepository('CituaoUsuarioBundle:Programa');
		$programa = $repository->findOneByCoordinador($coordinador);
		*/
		
		return $this->render('IngenieriaDirectorBundle:Default:profesores.html.twig', array('listaProfesores' => $listaProfesores, 'msgerr' => $msgerr));
	}
	
	
		/********************************************************/
	// Registra y modifica un profesor
	/********************************************************/		
	public function registrarProfesorAction(){

		$peticion = $this->getRequest();
		$em = $this->getDoctrine()->getManager();

		$profesor = new Profesor();

		$formulario = $this->createForm(new ProfesorType(), $profesor);
		$formulario->handleRequest($peticion);

		if ($formulario->isValid()) {
			//validamos que no existe el director
			$repository = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Profesor');
			$p = $repository->findOneBy(array('ci' => $profesor->getCi()));

			if ($p != NULL){
				throw $this->createNotFoundException('ERR_PROFESOR_REGISTRADO');
			}

		   // Completar las propiedades que el usuario no rellena en el formulario

			$em->persist($profesor);

			//los roles fueron cargados de forma manual en la base de datos
			//buscamos una instancia role tipo PROFESOR 
			
			$codigo = 2; //codigo ID q corresponde al director
			$repository = $this->getDoctrine()->getRepository('IngenieriaUsuarioBundle:Role');
			$role = $repository->findOneBy(array('id' => $codigo));

			if ($role == NULL){
				throw $this->createNotFoundException('ERR_ROLE_PROFESOR_NOREGISTRADO');
			}
			$usuario = new Usuario();
			//cargamos todos los atributos al usuario
			$usuario->setUsername($profesor->getCi());
			$usuario->setPassword($profesor->getCi());
			$usuario->setSalt(md5(time()));
			$usuario->addRole($role);  //cargamos el rol al coordinador

			//codificamos el password			
			$encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
			$passwordCodificado = $encoder->encodePassword($usuario->getPassword(), $usuario->getSalt());
			$usuario->setPassword($passwordCodificado);
			$em->persist($usuario);
			

			$em->flush();
			return $this->redirect($this->generateUrl('ingenieria_director_homepage'));
		}

		return $this->render('IngenieriaDirectorBundle:Default:registrarprofesor.html.twig', array(
			'formulario' => $formulario->createView()
			));		

	}	

	//*******************************************
	// Muestra las actividades de un profesor
	//*******************************************
	public function actividadesProfesorAction($id){
		$peticion = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$repository = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Profesor');
		$profesor = $repository->findOneBy(array('id' => $id));
		
		$actividades = $profesor->getActividades();

		if ($actividades->count() == 0) {
			$msgerr = array('descripcion'=>'No hay actividades registradas para el profesor!','id'=>'1');
		}else{
			$msgerr = array('descripcion'=>'','id'=>'0');
		}

		/*
				//buscamos el programa
		$user = $this->get('security.context')->getToken()->getUser();
		$coordinador =  $user->getUsername();
		$repository = $this->getDoctrine()->getRepository('CituaoUsuarioBundle:Programa');
		$programa = $repository->findOneByCoordinador($coordinador);
		*/
		return $this->render('IngenieriaDirectorBundle:Default:actividades.html.twig', array('listaActividades' => $actividades, 'msgerr' => $msgerr ));
			
	}

	//**********************************************************
	//Muestra la informacion de la actividad complementaria
	//**********************************************************
	public function actividadAction($id){
		$peticion = $this->getRequest();
		$em = $this->getDoctrine()->getManager();

		// buscamos el ID del asesor academico
		$repository = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Actividad');
		$actividad = $repository->findOneBy(array('id' => $id));
		
		/*
		//buscamos el programa
		$user = $this->get('security.context')->getToken()->getUser();
		$coordinador =  $user->getUsername();
		$repository = $this->getDoctrine()->getRepository('CituaoUsuarioBundle:Programa');
		$programa = $repository->findOneByCoordinador($coordinador);
		*/
		
		return $this->render('IngenieriaDirectorBundle:Default:actividad.html.twig', array('actividad' => $actividad ));
	}

	/********************************************************/
	//Muestra y modifica un profesor registrado en la base de datos
	/********************************************************/		
	public function matriculaAction($id){
		$peticion = $this->getRequest();
		$repository = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Actividad');
		$actividad = $repository->findOneBy(array('id' => $id));
		
		$estudiantes = $actividad->getEstudiantes();
		if ($estudiantes->count() == 0) {
			//throw $this->createNotFoundException('ERR_NO_HAY_PROGRAMA');
			$msgerr = array('id'=>1, 'descripcion' => 'No hay estudiantes matriculados para esta actividad complementaria!');
		}else{
			$msgerr = array('id'=>0, 'descripcion' => 'Ok');
		}
		
	
		return $this->render('IngenieriaDirectorBundle:Default:matricula.html.twig',  array('listaEstudiantes' => $estudiantes, 'msgerr' => $msgerr));
	}	

	/********************************************************/
	//Muestra y modifica un profesor registrado en la base de datos
	/********************************************************/		
	public function profesorAction($id){
		$peticion = $this->getRequest();
		$repository = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Profesor');
		$profesor = $repository->findOneBy(array('id' => $id));
		
        $formulario = $this->createForm(new ProfesorType(), $profesor);
		$formulario->handleRequest($peticion);

        if ($formulario->isValid()) {

			$em = $this->getDoctrine()->getManager();
            $em->persist($profesor);
            $em->flush();
            
            return $this->redirect($this->generateUrl('ingenieria_director_homepage'));
        }
		
        return $this->render('IngenieriaDirectorBundle:Default:profesor.html.twig', array('formulario' => $formulario->createView(), 'profesor' => $profesor ));
	}
}
