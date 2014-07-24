<?php

namespace Ingenieria\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Ingenieria\UsuarioBundle\Entity\Director;
use Ingenieria\UsuarioBundle\Entity\Usuario;
use Ingenieria\UsuarioBundle\Entity\Categoria;
use Ingenieria\UsuarioBundle\Entity\Document;
use Ingenieria\UsuarioBundle\Form\Type\DirectorType;
use Ingenieria\UsuarioBundle\Form\Type\ProfesorType;
use Ingenieria\UsuarioBundle\Form\Type\CategoriaType;
use Ingenieria\ProfesorBundle\Entity\Profesor;
use Ingenieria\EstudianteBundle\Entity\Estudiante;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
   public function indexAction()
    {
		
		if ($this->get('security.context')->isGranted('ROLE_COORDINADOR')) {
        	return $this->redirect($this->generateUrl('cituao_coord_homepage'));
    	}
		else{
			if ($this->get('security.context')->isGranted('ROLE_ESTUDIANTE')) {
				return $this->redirect($this->generateUrl('ingenieria_estudiante_homepage'));
			}else{
				if ($this->get('security.context')->isGranted('ROLE_PROFESOR')) {
					return $this->redirect($this->generateUrl('ingenieria_profesor_homepage'));
				}else {
				if ($this->get('security.context')->isGranted('ROLE_DIRECTOR')) {
					return $this->redirect($this->generateUrl('ingenieria_director_homepage'));
				}else {
					if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
						return $this->redirect($this->generateUrl('usuario_adm_homepage'));
				}
			}
		}			
		}
	}
	
    //para colocar en mantenimiento $msg = "M"  vacío para produccion $msg=""
	$msg = "";
	
     return $this->render('IngenieriaUsuarioBundle:Default:portal.html.twig', array("error"=>array("message"=>$msg)));
    }

	//*******************************************************
	//Proceso de autenticación 
	//*******************************************************
    public function loginAction()
    {
        $peticion = $this->getRequest();
        $sesion = $peticion->getSession();

        $error = $peticion->attributes->get(
            SecurityContext::AUTHENTICATION_ERROR,
            $sesion->get(SecurityContext::AUTHENTICATION_ERROR)
        );
        return $this->render(
            'IngenieriaUsuarioBundle:Default:portal.html.twig',
            array(
                // last username entered by the user
                'last_username' => $sesion->get(SecurityContext::LAST_USERNAME),
                'error'         => $error
            )
        );
    }
	
	//******************************************************
	// Home del administrador de la aplicacion
	//******************************************************
	public function homeAdmAction(){
	
		$repository = $this->getDoctrine()->getRepository('IngenieriaUsuarioBundle:Director');
		$directores = $repository->findAll();
		
		
		if (!$directores) {
			//throw $this->createNotFoundException('ERR_NO_HAY_PROGRAMA');
			$msgerr = array('id'=>1, 'descripcion' => 'No hay directores registrados en el sistema');
		}else{
			$msgerr = array('id'=>0, 'descripcion' => 'Ok');
		}
		return $this->render('IngenieriaUsuarioBundle:Default:directores.html.twig',  array('listaDirectores' => $directores, 'msgerr' => $msgerr));
	}
	
	/********************************************************/
	// Registra y modifica un director academico
	/********************************************************/		
	public function registrarDirectorAction(){

		$peticion = $this->getRequest();
		$em = $this->getDoctrine()->getManager();

		$director = new Director();

		$formulario = $this->createForm(new DirectorType(), $director);
		$formulario->handleRequest($peticion);

		if ($formulario->isValid()) {
			//validamos que no existe el director
			$repository = $this->getDoctrine()->getRepository('IngenieriaUsuarioBundle:Director');
			$d = $repository->findOneBy(array('ci' => $director->getCi()));

			if ($d != NULL){
				throw $this->createNotFoundException('ERR_DIRECTOR_REGISTRADO');
			}

		   // Completar las propiedades que el usuario no rellena en el formulario

			$em->persist($director);

			//los roles fueron cargados de forma manual en la base de datos
			//buscamos una instancia role tipo DIRECTOR 
			
			$codigo = 1; //codigo ID q corresponde al director
			$repository = $this->getDoctrine()->getRepository('IngenieriaUsuarioBundle:Role');
			$role = $repository->findOneBy(array('id' => $codigo));

			if ($role == NULL){
				throw $this->createNotFoundException('ERR_ROLE_NO_ENCONTRADO');
			}
			$usuario = new Usuario();
			//cargamos todos los atributos al usuario
			$usuario->setUsername($director->getCi());
			$usuario->setPassword($formulario->get('password')->getData());
			$usuario->setSalt(md5(time()));
			$usuario->addRole($role);  //cargamos el rol al coordinador
		
			//codificamos el password			
			$encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
			$passwordCodificado = $encoder->encodePassword($usuario->getPassword(), $usuario->getSalt());
			$usuario->setPassword($passwordCodificado);
			$usuario->setIsActive(true);		
			$em->persist($usuario);
			

			$em->flush();
			return $this->redirect($this->generateUrl('usuario_adm_homepage'));
		}

		return $this->render('IngenieriaUsuarioBundle:Default:registrardirector.html.twig', array(
			'formulario' => $formulario->createView()
			));		

	}		

	/********************************************************/
	//Muestra y modifica un director registrado en la base de datos
	/********************************************************/		
	public function directorAction($id){
		$peticion = $this->getRequest();
		$repository = $this->getDoctrine()->getRepository('IngenieriaUsuarioBundle:Director');
		$director = $repository->findOneBy(array('id' => $id));
		
        $formulario = $this->createForm(new DirectorType(), $director);
		$formulario->handleRequest($peticion);

        if ($formulario->isValid()) {
			$repository = $this->getDoctrine()->getRepository('IngenieriaUsuarioBundle:Usuario');
			$usuario = $repository->findOneBy(array('username' => $director->getCi()));

			
			$usuario->setUsername($director->getCi());
			$usuario->setPassword($formulario->get('password')->getData());
			$usuario->setSalt(md5(time()));
			//codificamos el password			
			$encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
			$passwordCodificado = $encoder->encodePassword($usuario->getPassword(), $usuario->getSalt());
			$usuario->setPassword($passwordCodificado);
			

			$em = $this->getDoctrine()->getManager();
            $em->persist($director);
			$em->persist($usuario);
            $em->flush();
            
            return $this->redirect($this->generateUrl('usuario_adm_homepage'));
        }
		
        return $this->render('IngenieriaUsuarioBundle:Default:director.html.twig', array('formulario' => $formulario->createView(), 'director' => $director ));
	}	
	
		//******************************************************
	// Home del administrador de la aplicacion
	//******************************************************
	public function estudiantesAction(){
		$document = new Document();
		$form = $this->createFormBuilder($document)
		->add('file')
		->add('name')
		->getForm();
	
		$repository = $this->getDoctrine()->getRepository('IngenieriaEstudianteBundle:Estudiante');
		$estudiantes = $repository->findAll();
		
		
		if (!$estudiantes) {
			//throw $this->createNotFoundException('ERR_NO_HAY_PROGRAMA');
			$msgerr = array('id'=>1, 'descripcion' => 'No hay estudiantes registrados en el sistema');
		}else{
			$msgerr = array('id'=>0, 'descripcion' => 'Ok');
		}
		return $this->render('IngenieriaUsuarioBundle:Default:estudiantes.html.twig',  array('listaEstudiantes' => $estudiantes,  'form' => $form->createView() , 'msgerr' => $msgerr));
	}
	
	//********************************************************************
	// SUBIR ESTUDIANTES A PARTIR DE UN CSV
	//********************************************************************
	public function subirEstudiantesAction(Request $request){
			
		$document = new Document();
		$form = $this->createFormBuilder($document)
		->add('file')
		->add('name')
		->getForm();

		$form->handleRequest($request);

		if ($form->isValid()) {
			//levantar servicios de doctrine base de datos
			$em = $this->getDoctrine()->getManager();
			//se copia el archivo al directorio del servidor			
			$document->upload();
			$em->persist($document);
		    //$em->flush();
			$archivo= $document->getAbsolutePath();		
			//bajamos el archivo a una matriz para procesar registro a registro y bajarlo a base de datos		    
			$filas = file($archivo);
			$i=0;
			$numero_fila= count($filas);	
			//para buscar si ya se encuentra en la base de datos
			$repository = $this->getDoctrine()->getRepository('IngenieriaEstudianteBundle:Estudiante');
			$nohay = true;			
			//procesamos la matriz separando los campos por medio del separador putno y coma
			while($i <= $numero_fila -1){
				$row = $filas[$i];
				//validamos que tenga el numero de columnas correctas
				$sql = explode(";",$row);

				$e = $repository->findOneBy(array('ci' => $sql[3]));
				//Si esta en la base de datos lo ignoramos				
				if ($e != NULL){
					$i++;						
					continue;
				}

				$listaEstudiantes[$i] =  array("codigo"=> $sql[0], "apellidos"=>$sql[1], "nombres"=>$sql[2], "ci" => $sql[3], 	
					 "emailInstitucional" => $sql[4] );
				$i++;
				$nohay = false;
			}
			
			
			if (!$nohay){
				
				//los roles fueron cargados de forma manual en la base de datos
				//buscamos una instancia role tipo practicante 
				$codigo = 3; //1 corresponde a practicantes		
				$repository = $this->getDoctrine()->getRepository('IngenieriaUsuarioBundle:Role');
				$role = $repository->findOneBy(array('id' => $codigo));

				/*buscamos el programa
				$user = $this->get('security.context')->getToken()->getUser();
				$coordinador =  $user->getUsername();
				$repository = $this->getDoctrine()->getRepository('IngenieriaUsuarioBundle:Programa');
				$programa = $repository->findOneByCoordinador($coordinador);*/
						
				/*				
				$repository = $this->getDoctrine()->getRepository('IngenieriaUsuarioBundle:Area');
				$area = $repository->findOneById($id_area);*/
				
				/*buscamos los periodos y el periodo actual
				$repository = $this->getDoctrine()->getRepository('CituaoUsuarioBundle:Periodo');
				$query = $repository->createQueryBuilder('p')
						->orderBy('p.id','DESC')
						->getQuery();
				$periodos = $query->getResult();
				foreach ($periodos as $periodoActual){
					break;
				}
				*/
				
				//procesamos la matriz  fila a fila creando practicantes y usuarios
				$i=0;				
				$sad = "";	
		
				while($i <= $numero_fila -1){
					//creamos una instancia Practicante para descargar datos del CSV y guardar en la base de datos
					$estudiante = new Estudiante();
					//creamos una instancia de usuario para darle entrada a los practicantes como usuarios en el sistema
					$usuario = new Usuario();

					//viene del archivo .csv	
					//cargamos todos los atributos al practicante
					$estudiante->setCodigo($listaEstudiantes[$i]['codigo']);
					$estudiante->setNombres($listaEstudiantes[$i]['nombres']);
					$estudiante->setApellidos($listaEstudiantes[$i]['apellidos']);
					$estudiante->setEmailInstitucional($listaEstudiantes[$i]['emailInstitucional']);
					$estudiante->setCi($listaEstudiantes[$i]['ci']);
					$estudiante->setAprobadoCronograma(false);
					//$practicante->setPrograma($programa);
					//$practicante->setPeriodo($periodoActual);
					
					//cargamos todos los atributos al usuario
					$usuario->setUsername($listaEstudiantes[$i]['codigo']) ;
					$usuario->setPassword($listaEstudiantes[$i]['ci']);
					$usuario->setSalt(md5(time()));
					$usuario->addRole($role); //cargamos el rol al coordinador
					$usuario->setIsActive(true); //tener acceso

					$encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
		            $passwordCodificado = $encoder->encodePassword($usuario->getPassword(), $usuario->getSalt());
					$usuario->setPassword($passwordCodificado);

					 $em->persist($usuario);

					$em->persist($estudiante);
					$em->flush();
					$i++;
				}
			}
		
			$msgerr = array('id'=>'0', 'descripcion'=>' ');
			return $this->render('IngenieriaUsuarioBundle:Default:subirestudiantes.html.twig', array('listaEstudiantes' => $listaEstudiantes , 'msgerr' => $msgerr));
			
			//return $this->redirect($this->generateUrl('cituao_coord_practicantes'));
		} 
		
		$msgerr = array('id'=>'0', 'descripcion'=>' ');
		/*
		//buscamos el programa
		$user = $this->get('security.context')->getToken()->getUser();
		$coordinador =  $user->getUsername();
		$repository = $this->getDoctrine()->getRepository('CituaoUsuarioBundle:Programa');
		$programa = $repository->findOneByCoordinador($coordinador);
		*/
		$listaEstudiantes= null;

		return $this->render('IngenieriaUsuarioBundle:Default:subirestudiantes.html.twig', array('listaEstudiantes' => $listaEstudiantes , 'msgerr' => $msgerr));
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
		
		return $this->render('IngenieriaUsuarioBundle:Default:profesores.html.twig', array('listaProfesores' => $listaProfesores, 'msgerr' => $msgerr));
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
            
            return $this->redirect($this->generateUrl('usuario_profesores'));
        }
		
        return $this->render('IngenieriaUsuarioBundle:Default:profesor.html.twig', array('formulario' => $formulario->createView(), 'profesor' => $profesor ));
	}	

	//******************************************************
	// Muestra todas las actividades
	//******************************************************
	public function actividadesAction(){
	
		$repository = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Actividad');
		$actividades = $repository->findAll();
		
		
		if (!$actividades) {
			//throw $this->createNotFoundException('ERR_NO_HAY_PROGRAMA');
			$msgerr = array('id'=>1, 'descripcion' => 'No hay actividades registradas en el sistema');
		}else{
			$msgerr = array('id'=>0, 'descripcion' => 'Ok');
		}
		return $this->render('IngenieriaUsuarioBundle:Default:actividades.html.twig',  array('listaActividades' => $actividades, 'msgerr' => $msgerr));
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
	
	//******************************************************************
	//Muestra cronograma del estudiantes
	//******************************************************************
	public function cronogramaAction($id){
    	$repository = $this->getDoctrine()->getRepository('IngenieriaEstudianteBundle:Estudiante');
    	$estudiante = $repository->findOneBy(array('id' => $id));
		
		if ($estudiante == NULL){
			throw $this->createNotFoundException('ERR_ESTUDIANTE_NO_ENCONTRADO');
		}
		if ($estudiante->getActividad() == null){
			throw $this->createNotFoundException('ERR_CRONOGRAMA_NO_ENCONTRADO');

		}
		
		//buscar cronograma
		$cronograma = $estudiante->getActividades();
			
		if ($cronograma->count()==0){
			$msgerr = array('descripcion'=>'No hay actividades registradas en el sistema!','id'=>'1');
		}
		else {
			$msgerr = array('descripcion'=>'','id'=>'0');
		}
		return $this->render('IngenieriaUsuarioBundle:Default:cronograma.html.twig', array('estudiante' => $estudiante, 'cronograma'=>$cronograma, 'msgerr' => $msgerr));
			
			
	}
	
		//******************************************************
	// Muestra todas las categorias
	//******************************************************
	public function categoriasAction(){
		$repository = $this->getDoctrine()->getRepository('IngenieriaUsuarioBundle:Categoria');
		$categorias = $repository->findAll();
		
		if (!$categorias) {
			//throw $this->createNotFoundException('ERR_NO_HAY_PROGRAMA');
			$msgerr = array('id'=>1, 'descripcion' => 'No hay categorias registradas en el sistema');
		}else{
			$msgerr = array('id'=>0, 'descripcion' => 'Ok');
		}
		return $this->render('IngenieriaUsuarioBundle:Default:categorias.html.twig',  array('listaActividades' => $categorias, 'msgerr' => $msgerr));
	}
	
	
		/********************************************************/
	// Registra y modifica una categoria
	/********************************************************/		
	public function registrarCategoriaAction(){

		$peticion = $this->getRequest();
		$em = $this->getDoctrine()->getManager();

		$categoria = new Categoria();

		$formulario = $this->createForm(new CategoriaType(), $categoria);
		$formulario->handleRequest($peticion);

		if ($formulario->isValid()) {
		   // Completar las propiedades que el usuario no rellena en el formulario

			$em->persist($categoria);
			$em->flush();
			return $this->redirect($this->generateUrl('usuario_categorias'));
		}

		return $this->render('IngenieriaUsuarioBundle:Default:registrarcategoria.html.twig', array(
			'formulario' => $formulario->createView()
			));		

	}	
}
