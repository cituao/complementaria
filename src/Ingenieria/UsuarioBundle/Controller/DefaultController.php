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
use Ingenieria\UsuarioBundle\Form\Type\GrupoType;
use Ingenieria\UsuarioBundle\Form\Type\GrupoActualizarType;
use Ingenieria\UsuarioBundle\Form\Type\ActividadType;
use Ingenieria\ProfesorBundle\Entity\Profesor;
use Ingenieria\ProfesorBundle\Entity\Actividad;
use Ingenieria\EstudianteBundle\Entity\Estudiante;
use Ingenieria\DirectorBundle\Entity\Grupo;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
   public function indexAction()
    {
		
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
	
	
	//**************************************************************************
	// Home del administrador de la aplicacion muestra los directores
	//**************************************************************************
	public function homeAdmAction(){
	
		$repository = $this->getDoctrine()->getRepository('IngenieriaDirectorBundle:Grupo');
		$grupos = $repository->findAll();
		
		
		if (!$grupos) {
			//throw $this->createNotFoundException('ERR_NO_HAY_PROGRAMA');
			$msgerr = array('id'=>1, 'descripcion' => '¡No hay cursos registrados!');
		}else{
			$msgerr = array('id'=>0, 'descripcion' => 'Ok');
		}
		return $this->render('IngenieriaUsuarioBundle:Default:grupos.html.twig',  array('listaGrupo' => $grupos, 'msgerr' => $msgerr));
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
	
	//********************************************************/
	//Elimina director
	/********************************************************/		
	public function eliminarDirectorAction($id){
		$peticion = $this->getRequest();
		$repository = $this->getDoctrine()->getRepository('IngenieriaUsuarioBundle:Director');
		$director = $repository->findOneBy(array('id' => $id));
		
		$em= $this->getDoctrine()->getManager();
		$em->remove($director);
		$em->flush();
            
        return $this->redirect($this->generateUrl('usuario_adm_homepage'));
	}	
	
	//********************************************************/
	//Elimina profesor
	/********************************************************/		
	public function eliminarProfesorAction($id){
		$peticion = $this->getRequest();
		$repository = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Profesor');
		$profesor = $repository->findOneBy(array('id' => $id));
		
		$em= $this->getDoctrine()->getManager();
		$em->remove($profesor);
		$em->flush();
            
        return $this->redirect($this->generateUrl('usuario_profesores'));
	}	
	
		//******************************************************
	// Home del administrador de la aplicacion
	//******************************************************
	public function estudiantesAction(){

		$repository = $this->getDoctrine()->getRepository('IngenieriaEstudianteBundle:Estudiante');
		$estudiantes = $repository->findAll();
		
		
		if (!$estudiantes) {
			//throw $this->createNotFoundException('ERR_NO_HAY_PROGRAMA');
			$msgerr = array('id'=>1, 'descripcion' => 'No hay estudiantes registrados en el sistema');
		}else{
			$msgerr = array('id'=>0, 'descripcion' => 'Ok');
		}
		return $this->render('IngenieriaUsuarioBundle:Default:estudiantes.html.twig',  array('listaEstudiantes' => $estudiantes, 'msgerr' => $msgerr));
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
					 "emailInstitucional" => $sql[4], "email" => $sql[5] );
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
					$estudiante->setEmail($listaEstudiantes[$i]['email']);
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
			return $this->redirect($this->generateUrl('usuario_profesores'));
		}

		return $this->render('IngenieriaUsuarioBundle:Default:registrarprofesor.html.twig', array(
			'formulario' => $formulario->createView()
			));		

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
		
	
		return $this->render('IngenieriaUsuarioBundle:Default:matricula.html.twig',  array('listaEstudiantes' => $estudiantes, 'msgerr' => $msgerr));
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
	
	
		/********************************************************/
	//Muestra y modifica una  categoria en la base de datos
	/********************************************************/		
	public function categoriaAction($id){
		$peticion = $this->getRequest();
		$repository = $this->getDoctrine()->getRepository('IngenieriaUsuarioBundle:Categoria');
		$categoria = $repository->findOneBy(array('id' => $id));
		
        $formulario = $this->createForm(new CategoriaType(), $categoria);
		$formulario->handleRequest($peticion);

        if ($formulario->isValid()) {
			$repository = $this->getDoctrine()->getRepository('IngenieriaUsuarioBundle:Categoria');
		
			$em->persist($categoria);
            $em->flush();
            
            return $this->redirect($this->generateUrl('usuario_categorias'));
        }
		
        return $this->render('IngenieriaUsuarioBundle:Default:categoria.html.twig', array('formulario' => $formulario->createView(), 'categoria' => $categoria ));
	}	

	/********************************************************/
	// Registra y modifica un grupo
	/********************************************************/		
	public function registrarGrupoAction(){

		$peticion = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		/*
		$repository_actividades = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Actividad');
		$actividades = $repository_actividades->findAll();
		if (!$actividades) {
			throw $this->createNotFoundException('ERR__NO_HAY_ACTIVIDAD');
		}
		*/

		
		//buscamos los profesores que no tienen asignado grupo		
		$repository_profesor = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Profesor');
		$profesores = $repository_profesor->findAll();
		$repository_grupo = $this->getDoctrine()->getRepository('IngenieriaDirectorBundle:Grupo');
	
		//RETIRAMOS ESTE CODIGO QUE RECOLECTABA LOS PROFESORES QUE NO TENIAN NINGUN CURSO ASIGNADO 
		//creamos un objeto de arreglos para los profesores		
		/*
		$profelibres= new \Doctrine\Common\Collections\ArrayCollection();

		foreach ($profesores as $p){
			//si lo encuentra pasamos sino lo agregamos al arreglo de objetos
			if ($grupo = $repository_grupo->findOneByTutor($p->getId()))
				continue;
			else
				$profelibres[]=$p;
		}
		*/
		
		$grupo = new Grupo();

		//$formulario = $this->createForm(new GrupoType($profelibres), $grupo);
		$formulario = $this->createForm(new GrupoType(), $grupo);
		$formulario->handleRequest($peticion);

		if ($formulario->isValid()) {
			//validamos que no existe el director
			$repository = $this->getDoctrine()->getRepository('IngenieriaDirectorBundle:Grupo');

		   // Completar las propiedades que el usuario no rellena en el formulario

			if ($grupo->getFile() != NULL) {
				
				$grupo->setPath('grupo');
				$grupo->upload();
				
				$archivo= $grupo->getAbsolutePath();		
				//bajamos el archivo a una matriz para procesar registro a registro y bajarlo a base de datos		    
				$filas = file($archivo.".csv");
				if (($gestor = fopen($archivo.".csv", "r")) == FALSE) {
					throw $this->createNotFoundException('ERR_LECTURA_CSV');
				}
				
				$i=0;
				$numero_filas=0;
				//$numero_fila= count($filas);	

				//para buscar si ya se encuentra en la base de datos
				$repository = $this->getDoctrine()->getRepository('IngenieriaEstudianteBundle:Estudiante');

				$nohay = true;
				$numero_registrados=0;
				$sw=false;
				//procesamos la matriz separando los campos por medio del separador putno y coma
				while (($datos = fgetcsv($gestor, 1000, ";")) !== FALSE) {
					if ($sw == false) {
						$sw = true;
						continue;
					}else 
					{
						//$numero = count($datos); lee numero de campos
						//echo "<p> $numero de campos en la línea $fila: <br /></p>\n";
						//$fila++;
						$e = $repository->findOneBy(array('ci' => $datos[5]));
						if ($e != NULL){
							$numero_registrados++;
						}else {
							$listaEstudiantes[$i] =  array("codigo"=> $datos[1], "apellidos"=>$datos[2], "nombres"=>$datos[3], "ci" => $datos[5],"emailInstitucional" => $datos[7], "emailpersonal" => $datos[8]);
							$nohay = false;
							$i++;
						}
					}
				}
				
				fclose($gestor);
				//los roles fueron cargados de forma manual en la base de datos
				//buscamos una instancia role tipo practicante 
				//$codigo = 3; //1 corresponde a practicantes		
				//$repository = $this->getDoctrine()->getRepository('IngenieriaUsuarioBundle:Role');
				//$role = $repository->findOneBy(array('id' => $codigo));

				//total de filas
				$numero_filas = $i-1;
				$i=0;
				while($i <= $numero_filas){
					//creamos una instancia Practicante para descargar datos del CSV y guardar en la base de datos
					$estudiante = new Estudiante();
					//creamos una instancia de usuario para darle entrada a los practicantes como usuarios en el sistema
					//$usuario = new Usuario();

					//viene del archivo .csv	
					//cargamos todos los atributos al practicante
					$estudiante->setCodigo($listaEstudiantes[$i]['codigo']);
					$estudiante->setNombres(utf8_encode($listaEstudiantes[$i]['nombres']));
					$estudiante->setApellidos(utf8_encode($listaEstudiantes[$i]['apellidos']));
					$estudiante->setEmailInstitucional($listaEstudiantes[$i]['emailInstitucional']);
					$estudiante->setEmail($listaEstudiantes[$i]['emailpersonal']);
					$estudiante->setCi($listaEstudiantes[$i]['ci']);
					$estudiante->setAprobadoCronograma(false);
					$estudiante->setGrupo($grupo);
					
					//cargamos todos los atributos al usuario
					/*
					$usuario->setUsername($listaEstudiantes[$i]['codigo']) ;
					$usuario->setPassword($listaEstudiantes[$i]['ci']);
					$usuario->setSalt(md5(time()));
					$usuario->addRole($role); //cargamos el rol al coordinador
					$usuario->setIsActive(true); //tener acceso

					$encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
		            $passwordCodificado = $encoder->encodePassword($usuario->getPassword(), $usuario->getSalt());
					$usuario->setPassword($passwordCodificado);
					$em->persist($usuario);
					*/
					$em->persist($estudiante);
					$i++;
				}
				$em->persist($grupo);	
				$em->flush();	
				//return $this->redirect($this->generateUrl('usuario_adm_homepage'));
				$msgerr = array('id'=>'0', 'descripcion'=>' ');
				return $this->render('IngenieriaUsuarioBundle:Default:subirestudiantes.html.twig', array('listaEstudiantes' => $listaEstudiantes , 'msgerr' => $msgerr));
			} 
		}
		return $this->render('IngenieriaUsuarioBundle:Default:registrargrupo.html.twig', array(
			'formulario' => $formulario->createView()
			));		
	}	


	/********************************************************/
	//Muestra y modifica un grupo registrado en la base de datos
	/********************************************************/		
	public function actualizargrupoAction($id){
		$peticion = $this->getRequest();
		$em = $this->getDoctrine()->getManager();


		$repository = $this->getDoctrine()->getRepository('IngenieriaDirectorBundle:Grupo');
		$grupo = $repository->findOneBy(array('id' => $id));		
	
		$formulario = $this->createForm(new GrupoActualizarType(), $grupo);
		
		$formulario->handleRequest($peticion);

		if ($formulario->isValid()) {
			$em->persist($grupo);
			$em->flush();
			return $this->redirect($this->generateUrl('usuario_adm_homepage'));
		}
		
        return $this->render('IngenieriaUsuarioBundle:Default:actualizargrupo.html.twig', array('formulario' => $formulario->createView(), 'grupo' => $grupo ));
		
	}

	/********************************************************/
	// Registrar actividad
	/********************************************************/		
	public function registrarActividadAction(){

		$peticion = $this->getRequest();
		$em = $this->getDoctrine()->getManager();

		$actividad = new Actividad();

		$formulario = $this->createForm(new ActividadType(), $actividad);
		$formulario->handleRequest($peticion);

		if ($formulario->isValid()) {
			
			$em->persist($actividad);

			$em->flush();
			return $this->redirect($this->generateUrl('usuario_actividades'));
		}

		return $this->render('IngenieriaUsuarioBundle:Default:registraractividad.html.twig', array(
			'formulario' => $formulario->createView()
			));	
	}

	/********************************************************/
	//Muestra y modifica una actividad
	/********************************************************/		
	public function actualizarActividadAction($id){
		$peticion = $this->getRequest();
		$em = $this->getDoctrine()->getManager();

		$repository = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Actividad');
		$actividad = $repository->findOneBy(array('id' => $id));		
	
		$formulario = $this->createForm(new ActividadType(), $actividad);
		
		$formulario->handleRequest($peticion);

		if ($formulario->isValid()) {
			$em->persist($actividad);
			$em->flush();
			return $this->redirect($this->generateUrl('usuario_actividades'));
		}
		
        return $this->render('IngenieriaUsuarioBundle:Default:actualizaractividad.html.twig', array('formulario' => $formulario->createView(), 'actividad' => $actividad ));
	}
	
	/********************************************************/
	//Muestra y modifica una actividad
	/********************************************************/		
	public function estudiantesGrupoAction($id){
		
		$repository = $this->getDoctrine()->getRepository('IngenieriaDirectorBundle:Grupo');
		$grupo = $repository->findOneBy(array('id' => $id));	
		
		$estudiantes = $grupo->getEstudiantes();

		if (!$estudiantes) {
			//throw $this->createNotFoundException('ERR_NO_HAY_PROGRAMA');
			$msgerr = array('id'=>1, 'descripcion' => 'ERROR NO hay estudiantes en el grupo');
		}else{
			$msgerr = array('id'=>0, 'descripcion' => 'Ok');
		}
		return $this->render('IngenieriaUsuarioBundle:Default:estudiantes.html.twig',  array('listaEstudiantes' => $estudiantes, 'msgerr' => $msgerr));
	
	}
	
		/**********************************************************************************/
	// Muestra bitacora de trabajo semanal del estudiante
	/**********************************************************************************/		
	public function bitacoraAction($id){
    	$repository = $this->getDoctrine()->getRepository('IngenieriaEstudianteBundle:Estudiante');
    	$estudiante = $repository->findOneBy(array('id' => $id));
		
		$bitacora = $estudiante->getBitacora();
		
		if (!$bitacora) {
			$msgerr = array('descripcion'=>'No hay actividades registradas!','id'=>'1');
		}else{
			$msgerr = array('descripcion'=>'','id'=>'0');
		}
		return $this->render('IngenieriaUsuarioBundle:Default:bitacora.html.twig', array('estudiante' => $estudiante, 'bitacora'=>$bitacora, 'msgerr' => $msgerr));
	}
	
		//********************************************************
	// Muestra un listado de subgrupos
	//******************************************************** 	
	public function subGruposAction($id){
		//localizamos al profesor
		$user = $this->get('security.context')->getToken()->getUser();
		$ci =  $user->getUsername();
		$repository = $this->getDoctrine()->getRepository('IngenieriaDirectorBundle:Grupo');
		$grupo = $repository->find($id);

		//obtenemos los cursos o grupos
		$subgrupos = $grupo->getSubgrupos(); 
		//Sino tiene grupo mensaje de advertencia
		if ($subgrupos->count() == 0) {
			$msgerr = array('descripcion'=>'No hay colectivos definidos!','id'=>'1');
		} 
		else {
			$msgerr = array('descripcion'=>'','id'=>'0');
		}
		return $this->render('IngenieriaUsuarioBundle:Default:subgrupos.html.twig', array('listaSubgrupos' => $subgrupos, 'grupo' => $grupo, 'msgerr' => $msgerr));
	}
	
		
	//********************************************************
	// Muestra un listado de estudiantes del grupos 
	//******************************************************** 	
	public function estudiantesSubgrupoAction($id){
	
		//buscamos el curso o grupo
		$repository = $this->getDoctrine()->getRepository('IngenieriaProfesorBundle:Subgrupo');
		$subgrupo = $repository->find($id);

		$estudiantes = $subgrupo->getEstudiantes();
	
		//Sino tiene grupo mensaje de advertencia
		if ($estudiantes->count() == 0) {
			$msgerr = array('descripcion'=>'¡No hay estudiantes registrados!','id'=>'1');
		} 
		else {
			$msgerr = array('descripcion'=>'','id'=>'0');
		}
		return $this->render('IngenieriaUsuarioBundle:Default:estudiantesubgrupo.html.twig', array('listaEstudiantes' => $estudiantes, 'subgrupo' => $subgrupo, 'msgerr' => $msgerr));
	}
}
