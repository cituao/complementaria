<?php

namespace Ingenieria\EstudianteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ingenieria\EstudianteBundle\Entity;

class DefaultController extends Controller
{
    public function indexAction()
    {
		$user = $this->get('security.context')->getToken()->getUser();
    	$codigo =  $user->getUsername();
    	$repository = $this->getDoctrine()->getRepository('IngenieriaEstudianteBundle:Estudiante');
    	$estudiante = $repository->findOneBy(array('codigo' => $codigo));
		
		if ($estudiante == NULL){
			throw $this->createNotFoundException('ERR_ESTUDIANTE_NO_ENCONTRADO');
		}
		//si el estudiante no tiene actividad se les presenta las activiades disponibles
		if ($estudiante->getActividad() == null){
			$nohaycupo = 0;
			//obtenemos las actividad complementarias con cupos
			$repository = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Actividad');
			$query = $repository->createQueryBuilder('a')
					->where('a.numeroCupos > :nohaycupo')
					->setParameter('nohaycupo', $nohaycupo)
					->getQuery();
					
			//->setParameter('id_programa', $programa->getId())
			$listaActividades = $query->getResult();

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
			
			return $this->render('IngenieriaEstudianteBundle:Default:actividades.html.twig', array('listaActividades' => $listaActividades, 'msgerr' => $msgerr));
		
		
		} 
		else {
			//buscamos el cronograma del estudiante
			$cronograma = $estudiante->getActividades();
			
			if  ($cronograma->count() == 0) {
				$msgerr = array('descripcion'=>'Aun no ha subido el cronograma','id'=>'1');
			}else {
				$msgerr = array('descripcion'=>'','id'=>'0');
			}
			
			return $this->render('IngenieriaEstudianteBundle:Default:cronograma.html.twig', array('p' => $estudiante, 'programa' => $programa, 'cronograma' => $cronograma));	
		}
		return $this->render('IngenieriaEstudianteBundle:Default:actividades.html.twig', array('listaActividades' => $listaActividades, 'msgerr' => $msgerr));
    }

	public function cronogramaAction(){
	 	$user = $this->get('security.context')->getToken()->getUser();
    	$codigo =  $user->getUsername();
    	$repository = $this->getDoctrine()->getRepository('IngenieriaEstudianteBundle:Estudiante');
    	$estudiante = $repository->findOneBy(array('codigo' => $codigo));
		
		if ($estudiante == NULL){
			throw $this->createNotFoundException('ERR_ESTUDIANTE_NO_ENCONTRADO');
		}
		if ($estudiante->getActividad() == null){
			$nohaycupo = 0;
			//obtenemos las actividad complementarias con cupos
			$repository = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Actividad');
			$query = $repository->createQueryBuilder('a')
					->where('a.numeroCupos > :nohaycupo')
					->setParameter('nohaycupo', $nohaycupo)
					->getQuery();
					
			//->setParameter('id_programa', $programa->getId())
			$listaActividades = $query->getResult();

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
			
			return $this->render('IngenieriaEstudianteBundle:Default:avisonoinscripto.html.twig');
		}
		else{
			return $this->render('IngenieriaEstudianteBundle:Default:cronograma.html.twig', array('msgerr' => $msgerr));
			
		}	
	}

	//********************************************************
	// Muestra un listado de actividades con cupos disponibles
	//******************************************************** 	
	public function actividadesAction(){
		$nohaycupo = 0;
		//obtenemos las actividad complementarias con cupos
		$repository = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Actividad');
		$query = $repository->createQueryBuilder('a')
				->where('a.numeroCupos > :nohaycupo')
				->setParameter('nohaycupo', $nohaycupo)
				->getQuery();
				
		//->setParameter('id_programa', $programa->getId())
		$listaActividades = $query->getResult();

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
		
		return $this->render('IngenieriaEstudianteBundle:Default:actividades.html.twig', array('listaActividades' => $listaActividades, 'msgerr' => $msgerr));
	}

	//**********************************************************
	//Muestra la informacion de la actividad complementaria
	//**********************************************************
	public function actividadAction($id){
		$em = $this->getDoctrine()->getManager();

		// buscamos el ID del asesor academico
		$repository = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Actividad');
		$actividad = $repository->findOneBy(array('id' => $id));
	
		return $this->render('IngenieriaEstudianteBundle:Default:actividad.html.twig', array('actividad' => $actividad));
	}

	public function inscripcionAction($id){
		$datos = array('idActividad'=>$id);

		return $this->render('IngenieriaEstudianteBundle:Default:inscripcion.html.twig', array('datos' => $datos));
	}
	
	public function confirmarAction($id){
		//buscamos el estudiante
		
		$user = $this->get('security.context')->getToken()->getUser();
    	$codigo =  $user->getUsername();
    	$repository = $this->getDoctrine()->getRepository('IngenieriaEstudianteBundle:Estudiante');
    	$estudiante = $repository->findOneBy(array('codigo' => $codigo));
		
		if ($estudiante == NULL){
			throw $this->createNotFoundException('ERR_ESTUDIANTE_NO_ENCONTRADO');
		}
		
		//buscamos la actividad que el estudiante quiere cursar
		$repository = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Actividad');
		$actividad = $repository->findOneBy(array('id' => $id));
		
		$estudiante->setActividad($actividad);
		
		$em = $this->getDoctrine()->getManager();
		
		$em->persist($estudiante);
		$em->flush();
		
		return $this->render('IngenieriaEstudianteBundle:Default:confirmacion.html.twig');
	}


}
