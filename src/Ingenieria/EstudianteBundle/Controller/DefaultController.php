<?php

namespace Ingenieria\EstudianteBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ingenieria\EstudianteBundle\Entity;
use Ingenieria\EstudianteBundle\Entity\Cronograma;
use Ingenieria\EstudianteBundle\Entity\Bitacora;
use Ingenieria\EstudianteBundle\Form\Type\BitacoraType;
use \DateTime;

class DefaultController extends Controller
{
	//******************************************************************************************************
	//Al iniciar sesion el estudiante debe mostrarse las ofertas o el cornograma de actividades 
	//SI EL PROCESO DE INSCRIPCION SE CIERRA COMENTAR LAS VISTA avisonoinscripto.html
	//y descomentar la vista inscripcion cerradas
	//******************************************************************************************************
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
			
			//si el proceso de inscripcion se cierra mostrar esta vista
			//return $this->render('IngenieriaEstudianteBundle:Default:inscripcioncerrada.html.twig');
			//si el proceso de inscripcion esta en curso descomentar esta vista
			return $this->render('IngenieriaEstudianteBundle:Default:avisonoinscripto.html.twig');
		}
		else{
			$user = $this->get('security.context')->getToken()->getUser();
			$codigo =  $user->getUsername();
			$repository = $this->getDoctrine()->getRepository('IngenieriaEstudianteBundle:Estudiante');
			$estudiante = $repository->findOneBy(array('codigo' => $codigo));
			
			$bitacora = $estudiante->getBitacora();
			
			if (!$bitacora) {
				$msgerr = array('descripcion'=>'No hay actividades registradas!','id'=>'1');
			}else{
				$msgerr = array('descripcion'=>'','id'=>'0');
			}
			return $this->render('IngenieriaEstudianteBundle:Default:bitacora.html.twig', array('estudiante' => $estudiante, 'bitacora'=>$bitacora, 'msgerr' => $msgerr));
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
		
		return $this->render('IngenieriaEstudianteBundle:Default:actividades.html.twig', array('listaActividades' => $listaActividades, 'msgerr' => $msgerr));
	}

	//****************************************************************************
	//Muestra la informacion de la actividad complementarias ofertadas
	//****************************************************************************
	public function actividadAction($id){
		$em = $this->getDoctrine()->getManager();
		
		$user = $this->get('security.context')->getToken()->getUser();
    	$codigo =  $user->getUsername();
    	$repository = $this->getDoctrine()->getRepository('IngenieriaEstudianteBundle:Estudiante');
    	$estudiante = $repository->findOneBy(array('codigo' => $codigo));
		
	 	$user = $this->get('security.context')->getToken()->getUser();
    	$codigo =  $user->getUsername();
    	$repository = $this->getDoctrine()->getRepository('IngenieriaEstudianteBundle:Estudiante');
    	$estudiante = $repository->findOneBy(array('codigo' => $codigo));		

		// buscamos el ID del asesor academico
		$repository = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Actividad');
		$actividad = $repository->findOneBy(array('id' => $id));
	
		if ($estudiante->getActividad()) 
			$inscripto = true; 
		else	
			$inscripto = false;
		
		if ($inscripto)
			return $this->render('IngenieriaEstudianteBundle:Default:actividad.html.twig', array('actividad' => $actividad));
		else	
			return $this->render('IngenieriaEstudianteBundle:Default:actividades_ofertadas.html.twig', array('actividad' => $actividad));
	
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
		//buscamos el subgrupo
		$repository_subgrupo = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Subgrupo');
		$subgrupo = $repository_subgrupo->find($estudiante->getSubgrupo()->getId());
		
		$estudiante->setActividad($actividad);
		$subgrupo->setActividad($actividad);
		//bajamos numero de cupos
		$ncupos = 0;
		$ncupos = $actividad->getNumeroCupos();
		$ncupos = $ncupos - 1;
		$actividad->setNumeroCupos($ncupos);

		$em = $this->getDoctrine()->getManager();
		$em->persist($actividad);
		$em->persist($estudiante);
		$em->persist($subgrupo);
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

		return $this->render('IngenieriaEstudianteBundle:Default:infoactividad.html.twig', array('actividad' => $actividad));

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

	/**********************************************************************************/
	// Mostrar datos del tutor
	/**********************************************************************************/		
	public function tutorAction(){
		$user = $this->get('security.context')->getToken()->getUser();
    	$codigo =  $user->getUsername();
    	$repository = $this->getDoctrine()->getRepository('IngenieriaEstudianteBundle:Estudiante');
    	$estudiante = $repository->findOneBy(array('codigo' => $codigo));
		
		$grupo = $estudiante->getGrupo();
		$tutor = $grupo->getTutor();
		
		return $this->render('IngenieriaEstudianteBundle:Default:tutor.html.twig', array('tutor' => $tutor));
	}
	
	/**********************************************************************************/
	// Muestra bitacora de trabajo semanal
	/**********************************************************************************/		
	public function bitacoraAction(){
		$user = $this->get('security.context')->getToken()->getUser();
    	$codigo =  $user->getUsername();
    	$repository = $this->getDoctrine()->getRepository('IngenieriaEstudianteBundle:Estudiante');
    	$estudiante = $repository->findOneBy(array('codigo' => $codigo));
		
		$bitacora = $estudiante->getBitacora();
		
		if (!$bitacora) {
			$msgerr = array('descripcion'=>'No hay actividades registradas!','id'=>'1');
		}else{
			$msgerr = array('descripcion'=>'','id'=>'0');
		}
		
		return $this->render('IngenieriaEstudianteBundle:Default:bitacora.html.twig', array('estudiante' => $estudiante, 'bitacora'=>$bitacora, 'msgerr' => $msgerr));
	}
	
	/**********************************************************************************/
	// Muestra bitacora de trabajo semanal
	/**********************************************************************************/		
	public function registrarActividadSemanalAction(){
		$peticion = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$user = $this->get('security.context')->getToken()->getUser();
    	$codigo =  $user->getUsername();
    	$repository = $this->getDoctrine()->getRepository('IngenieriaEstudianteBundle:Estudiante');
    	$estudiante = $repository->findOneBy(array('codigo' => $codigo));

		$bitacora = new Bitacora();

		$formulario = $this->createForm(new BitacoraType(), $bitacora);
		$formulario->handleRequest($peticion);

		if ($formulario->isValid()) {
			$bitacora->setEstudiante($estudiante);
			$em->persist($bitacora);

			$em->flush();
			return $this->redirect($this->generateUrl('ingenieria_estudiante_bitacora'));
		}

		return $this->render('IngenieriaEstudianteBundle:Default:registrar_actividad_semanal.html.twig', array(
			'formulario' => $formulario->createView()
			));	
	}
	
	/********************************************************/
	//Muestra y modifica una actividad
	/********************************************************/		
	public function actualizarActividadSemanalAction($id){
		$peticion = $this->getRequest();
		$em = $this->getDoctrine()->getManager();

		$repository = $this->getDoctrine()->getRepository('IngenieriaEstudianteBundle:Bitacora');
		$actividad_semanal = $repository->findOneBy(array('id' => $id));		
	
		$formulario = $this->createForm(new BitacoraType(), $actividad_semanal);
		
		$formulario->handleRequest($peticion);

		if ($formulario->isValid()) {
			$em->persist($actividad_semanal);
			$em->flush();
			return $this->redirect($this->generateUrl('ingenieria_estudiante_bitacora'));
		}
		
        return $this->render('IngenieriaEstudianteBundle:Default:actualizar_actividad_semanal.html.twig', array('formulario' => $formulario->createView(), 'actividad' => $actividad_semanal ));
	}
	
}
