<?php

namespace Ingenieria\EstudianteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ingenieria\EstudianteBundle\Entity;

class DefaultController extends Controller
{
    public function indexAction()
    {

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

	public function cronogramaAction(){
	 	$user = $this->get('security.context')->getToken()->getUser();
    	$codigo =  $user->getUsername();
    	$repository = $this->getDoctrine()->getRepository('IngenieriaEstudianteBundle:Estudiante');
    	$estudiante = $repository->findOneBy(array('codigo' => $codigo));

		//busco al academico
    	//$repository = $this->getDoctrine()->getRepository('CituaoAcademicoBundle:Academico');
    	//$academico = $repository->findOneBy(array('id' => $practicante->getAcademico()->getId()));

		//buscamos el cronograma del asesor academico
		/*    	
		$em = $this->getDoctrine()->getManager();
    	$query = $em->createQuery(
    		'SELECT c FROM CituaoAcademicoBundle:Cronograma c WHERE c.academico =:id_aca AND c.practicante =:id_pra');
    	$query->setParameter('id_aca',$academico->getId());
    	$query->setParameter('id_pra',$practicante->getId());
    	$cronograma = $query->getOneOrNullResult();
		*/
		//buscamos el cronograma del asesor externo
		/*    	
		$query = $em->createQuery(
    		'SELECT c FROM CituaoExternoBundle:Cronogramaexterno c WHERE c.externo =:id_ext AND c.practicante =:id_pra');
    	$query->setParameter('id_ext',$practicante->getExterno()->getId());
    	$query->setParameter('id_pra',$practicante->getId());
    	$cronogramaexterno = $query->getOneOrNullResult();
		*/
		$practicante = null;
		$cronogramaexterno = null;
		$cronograma = null;
		$programa = null;
		return $this->render('IngenieriaEstudianteBundle:Default:cronograma.html.twig', array('p' => $practicante, 'e' => $cronogramaexterno, 'a' => $cronograma, 'programa' => $programa));				

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
		

		return $this->render('IngenieriaEstudianteBundle:Default:inscripcion.html.twig');
	}


}
