<?php

namespace Ingenieria\DirectorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ingenieria\ProfesorBundle\Entity\Profesor;


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
}
