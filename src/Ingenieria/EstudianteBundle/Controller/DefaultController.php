<?php

namespace Ingenieria\EstudianteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ingenieria\EstudianteBundle\Entity;

class DefaultController extends Controller
{
    public function indexAction()
    {
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
		
		return $this->render('IngenieriaEstudianteBundle:Default:actividades.html.twig', array('listaActividades' => $listaActividades, 'msgerr' => $msgerr));
	}
}
