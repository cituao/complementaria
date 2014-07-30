<?php

namespace Ingenieria\EstudianteBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ingenieria\EstudianteBundle\Entity;
use Ingenieria\EstudianteBundle\Entity\Cronograma;
use \DateTime;

class DefaultController extends Controller
{
	//******************************************************************************************
	//
	//******************************************************************************************
    public function indexAction()
    {
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
			//buscar cronograma
			$cronograma = $estudiante->getActividades();
				
			if ($cronograma->count()==0){
				$msgerr = array('descripcion'=>'No hay actividades registradas en el sistema!','id'=>'1');
			}
			else {
				$msgerr = array('descripcion'=>'','id'=>'0');
			}
			return $this->render('IngenieriaEstudianteBundle:Default:cronograma.html.twig', array('estudiante' => $estudiante, 'cronograma'=>$cronograma, 'msgerr' => $msgerr));
			
		}	
	}

	//***************************************************
	// Mostrar el cronograma del estudiantes
	//***************************************************
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
			//buscar cronograma
			$cronograma = $estudiante->getActividades();
				
			if ($cronograma->count()==0){
				$msgerr = array('descripcion'=>'No hay actividades registradas en el sistema!','id'=>'1');
			}
			else {
				$msgerr = array('descripcion'=>'','id'=>'0');
			}
			return $this->render('IngenieriaEstudianteBundle:Default:cronograma.html.twig', array('estudiante' => $estudiante, 'cronograma'=>$cronograma, 'msgerr' => $msgerr));
			
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

	//*****************************************************************************************
	//Muestra una interfaz para que el estudiante se inscriba en la actividad que el selecciono
	//*****************************************************************************************
	public function inscripcionAction($id){
		$datos = array('idActividad'=>$id);

		return $this->render('IngenieriaEstudianteBundle:Default:inscripcion.html.twig', array('datos' => $datos));
	}
	
	//****************************************************************
	//Asigna una actividad al estudiante y actualiza cantidad de cupos
	//****************************************************************
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
		//bajamos numero de cupos
		$ncupos = $actividad->getNumeroCupos();
		$ncupos = $ncupos--;
		$actividad->setNumeroCupos($ncupos);

		$em = $this->getDoctrine()->getManager();
		$em->persist($actividad);
		$em->persist($estudiante);
		$em->flush();
		
		return $this->render('IngenieriaEstudianteBundle:Default:confirmacion.html.twig');
	}

	/**********************************************************************/
	// Registra una actividad en el cronograma el paso de datos es por ajax
	/**********************************************************************/		
	public function agregaractividadAction(){
		//buscamos el estudiante
		$user = $this->get('security.context')->getToken()->getUser();
    	$codigo =  $user->getUsername();
    	$repository = $this->getDoctrine()->getRepository('IngenieriaEstudianteBundle:Estudiante');
    	$estudiante = $repository->findOneBy(array('codigo' => $codigo));
	
		$request = $this->getRequest();
		$fecha = $request->request->get('fecha');
		$nombre_actividad = $request->request->get('nombre');
		
		
		$em = $this->getDoctrine()->getManager();
		
		$cronograma = new Cronograma();
		
		$cronograma->setNombreActividad($nombre_actividad);
		
		$cronograma->setEstado(false);
		$cronograma->setEstudiante($estudiante);
		
		
		 //convertimos la fecha de matricula a un objeto Date				
		
		$separa = explode("-",$fecha);
		$dia = $separa[0];
		$mes = $separa[1];
		$ano = $separa[2];

		$f = new \DateTime();
		$f->setDate($ano,$mes,$dia);

		$cronograma->setFechaEntrega($f);

		
		
		$em->persist($cronograma);
		$em->flush();
		
		$r = array("fecha" => $fecha, "nombre" => $nombre_actividad);
		$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new 
			JsonEncoder()));
		$json = $serializer->serialize($r, 'json');
		
			
		return new Response($json);
		//return $this->redirect($this->generateUrl('cituao_coord_practicantes'));
	}	
	
	//***********************************************+
	// Mostrar informacion de la actividad matriculada
	//************************************************	
	public function actividadMatriculadaAction(){
		//buscamos el estudiante
		$user = $this->get('security.context')->getToken()->getUser();
    	$codigo =  $user->getUsername();
    	$repository = $this->getDoctrine()->getRepository('IngenieriaEstudianteBundle:Estudiante');
    	$estudiante = $repository->findOneBy(array('codigo' => $codigo));

		$actividad = $estudiante->getActividad();

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

			return $this->render('IngenieriaEstudianteBundle:Default:infoactividad.html.twig', array('actividad' => $actividad));
		}
	}	

	/**********************************************************************/
	// Registra una actividad en el cronograma el paso de datos es por ajax
	/**********************************************************************/		
	public function eliminaractividadAction(){
		//buscamos el estudiante
		$user = $this->get('security.context')->getToken()->getUser();
    	$codigo =  $user->getUsername();
    	$repository = $this->getDoctrine()->getRepository('IngenieriaEstudianteBundle:Estudiante');
    	$estudiante = $repository->findOneBy(array('codigo' => $codigo));
		
		$request = $this->getRequest();
		$fecha = $request->request->get('fecha');
		
		$em = $this->getDoctrine()->getManager();
		
		 //convertimos la fecha de matricula a un objeto Date				
		$separa = explode("/",$fecha);
		$dia = $separa[0];
		$mes = $separa[1];
		$ano = $separa[2];
				

		$f = new \DateTime();
		$f->setDate($ano,$mes,$dia);
	
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
				'DELETE IngenieriaEstudianteBundle:Cronograma c
				WHERE c.fechaEntrega = :fecha AND c.estudiante = :id_estudiante')
				->setParameter('fecha', $f->format('Y-m-d'))
				->setParameter('id_estudiante', $estudiante->getId());
		$query->execute();			

		$r = array("fecha" => $f->format('Y-m-d'), "nombre" => 'SATISFACTORIO!');
		$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new 
			JsonEncoder()));
		$json = $serializer->serialize($r, 'json');
			
		return $this->redirect($this->generateUrl('ingenieria_estudiante_cronograma'));		
		//return new Response($json);
		
	}	

}
