<?php

namespace Ingenieria\ProfesorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ingenieria\EstudianteBundle\Entity\Estudiante;
use Ingenieria\ProfesorBundle\Entity\Actividad;
use Ingenieria\ProfesorBundle\Entity\Profesor;
use Ingenieria\ProfesorBundle\Form\Type\ActividadType;

class DefaultController extends Controller
{
    public function indexAction()
    {
		//localizamos al profesor
		$user = $this->get('security.context')->getToken()->getUser();
		$ci =  $user->getUsername();
		$repository = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Profesor');
		$profesor = $repository->findOneBy(array('ci' => $ci));

		$hayEstudiantes=false;
		$listaActividades = $profesor->getActividades();
		
		foreach($listaActividades as $actividad) {
			$listaEstudiantes = $actividad->getEstudiantes();
			if ($listaEstudiantes->count() != 0) {
				$hayEstudiantes = true;
				break;
			}
		}
		
		if (!$hayEstudiantes) {
			$msgerr = array('descripcion'=>'No hay estudiantes registrados!','id'=>'1');
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
		
		
		return $this->render('IngenieriaProfesorBundle:Default:estudiantes.html.twig', array('listaActividades' => $listaActividades, 'msgerr' => $msgerr));
       
		
    }

	//********************************************************
	// Muestra un listado de estudiantes
	//******************************************************** 	
	public function estudiantesAction(){
		//localizamos al profesor
		$user = $this->get('security.context')->getToken()->getUser();
		$ci =  $user->getUsername();
		$repository = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Profesor');
		$profesor = $repository->findOneBy(array('ci' => $ci));

		$hayEstudiantes=false;
		$listaActividades = $profesor->getActividades();
		
		foreach($listaActividades as $actividad) {
			$listaEstudiantes = $actividad->getEstudiantes();
			if ($listaEstudiantes->count() != 0) {
				$hayEstudiantes = true;
				break;
			}
		}
		
		if (!$hayEstudiantes) {
			$msgerr = array('descripcion'=>'No hay estudiantes registrados!','id'=>'1');
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
		
		
		return $this->render('IngenieriaProfesorBundle:Default:estudiantes.html.twig', array('listaActividades' => $listaActividades, 'msgerr' => $msgerr));
	}

	//********************************************************
	// Muestra un listado de actividades del profesor
	//******************************************************** 	
	public function actividadesAction(){
		//localizamos al profesor
		$user = $this->get('security.context')->getToken()->getUser();
		$ci =  $user->getUsername();
		$repository = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Profesor');
		$profesor = $repository->findOneBy(array('ci' => $ci));

		//$repository = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Actividad');
		//$listaActividades = $repository->findAll();
		
		$listaActividades =  $profesor->getActividades();
		if ( $listaActividades == null) {
			$msgerr = array('descripcion'=>'No hay actividades registradas!','id'=>'1');
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
		
		return $this->render('IngenieriaProfesorBundle:Default:actividades.html.twig', array('listaActividades' => $listaActividades, 'msgerr' => $msgerr));
	}

	/********************************************************/
	// Registra una actividad
	/********************************************************/		
	public function registrarActividadAction(){
		$user = $this->get('security.context')->getToken()->getUser();
		$ci =  $user->getUsername();
		$repository = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Profesor');
		$profesor = $repository->findOneBy(array('ci' => $ci));


		$peticion = $this->getRequest();
		$em = $this->getDoctrine()->getManager();

		$actividad = new Actividad();

		$formulario = $this->createForm(new ActividadType(), $actividad);
		$formulario->handleRequest($peticion);

		if ($formulario->isValid()) {
			$actividad->setProfesor($profesor);
			$em->persist($actividad);

			$em->flush();
			return $this->redirect($this->generateUrl('ingenieria_profesor_homepage'));
		}

		return $this->render('IngenieriaProfesorBundle:Default:registraractividades.html.twig', array(
			'formulario' => $formulario->createView()
			));		
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
		
		$formulario = $this->createForm(new ActividadType(), $actividad);
		$formulario->handleRequest($peticion);

		if ($formulario->isValid()) {
			$em->persist($actividad);
			$em->flush();

			return $this->redirect($this->generateUrl('ingenieria_profesor_homepage'));
		}
		/*
		//buscamos el programa
		$user = $this->get('security.context')->getToken()->getUser();
		$coordinador =  $user->getUsername();
		$repository = $this->getDoctrine()->getRepository('CituaoUsuarioBundle:Programa');
		$programa = $repository->findOneByCoordinador($coordinador);
		*/
		
		return $this->render('IngenieriaProfesorBundle:Default:actividad.html.twig', array('formulario' => $formulario->createView(), 'actividad' => $actividad ));
	}
	
	//*****************************************************************
	//Muestra el cronograma de actividades de un estudiante
	//*****************************************************************
	
	public function cronogramaAction($id){
		$repository = $this->getDoctrine()->getRepository('IngenieriaEstudianteBundle:Estudiante');
		$estudiante = $repository->findOneBy(array('id' => $id));
	
		$cronograma = null;
		$cronograma = $estudiante->getActividades();
		
		if ( $cronograma->count()  == 0 ) {
			$msgerr = array('descripcion'=>'¡No ha subido el cronograma de actividades!','id'=>'1');
		}else{
			$msgerr = array('descripcion'=>'','id'=>'0');
		}
		
		return $this->render('IngenieriaProfesorBundle:Default:cronograma.html.twig', array('estudiante' => $estudiante, 'cronograma' => $cronograma, 'msgerr' => $msgerr ));
	}

	//****************************************************************
	// Coloca true el campo de aprobado en el estudiante
	//****************************************************************
	public function aprobarAction($id){
		
		$repository = $this->getDoctrine()->getRepository('IngenieriaEstudianteBundle:Estudiante');
		$estudiante = $repository->findOneBy(array('id' => $id));
	
		$em = $this->getDoctrine()->getManager();

		$estudiante->setAprobadoCronograma(true);
		$em->persist($estudiante);		
		$em->flush();

		$cronograma = null;
		$cronograma = $estudiante->getActividades();

		if ( $cronograma->count()  == 0 ) {
			$msgerr = array('descripcion'=>'¡No ha subido el cronograma de actividades!','id'=>'0');
		}else{
			$msgerr = array('descripcion'=>'','id'=>'1');
		}
		
		return $this->render('IngenieriaProfesorBundle:Default:cronograma.html.twig', array('estudiante' => $estudiante, 'cronograma' => $cronograma, 'msgerr' => $msgerr ));
	}


	
}
