<?php

namespace Ingenieria\ProfesorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ingenieria\Estudiante\Entity\Estudiante;
use Ingenieria\Profesor\Entity\Actividad;

class DefaultController extends Controller
{
    public function indexAction()
    {
 		$repository = $this->getDoctrine()->getRepository('IngenieriaEstudianteBundle:Estudiante');
		$listaEstudiantes = $repository->findAll();
		
		if (!$listaEstudiantes) {
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
		
		return $this->render('IngenieriaProfesorBundle:Default:estudiantes.html.twig', array('listaEstudiantes' => $listaEstudiantes, 'msgerr' => $msgerr));
       
		
    }

	//********************************************************
	// Muestra un listado de estudiantes
	//******************************************************** 	
	public function estudiantesAction(){
		$repository = $this->getDoctrine()->getRepository('IngenieriaEstudianteBundle:Estudiante');
		$listaEstudiantes = $repository->findAll();
		
		if (!$listaEstudiantes) {
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
		
		return $this->render('IngenieriaProfesorBundle:Default:estudiantes.html.twig', array('listaEstudiantes' => $listaEstudiantes, 'msgerr' => $msgerr));
	}

	//********************************************************
	// Muestra un listado de actividades del profesor
	//******************************************************** 	
	public function actividadesAction(){
		$repository = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Actividad');
		$listaActividades = $repository->findAll();
		
		if (!$listaActividades) {
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

	
}
