<?php

namespace Ingenieria\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Ingenieria\UsuarioBundle\Entity\Director;
use Ingenieria\UsuarioBundle\Form\Type\DirectorType;

class DefaultController extends Controller
{
   public function indexAction()
    {
		
		if ($this->get('security.context')->isGranted('ROLE_COORDINADOR')) {
        	return $this->redirect($this->generateUrl('cituao_coord_homepage'));
    	}
		else{
			if ($this->get('security.context')->isGranted('ROLE_PRACTICANTE')) {
				return $this->redirect($this->generateUrl('cituao_practicante_homepage'));
			}else{
				if ($this->get('security.context')->isGranted('ROLE_ASESOR_EXT')) {
					return $this->redirect($this->generateUrl('cituao_externo_homepage'));
				}else {
				if ($this->get('security.context')->isGranted('ROLE_ASESOR_ACA')) {
					return $this->redirect($this->generateUrl('cituao_academico_homepage'));
				}else {
					if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
						return $this->redirect($this->generateUrl('usuario_adm_homepage'));
				}
			}
		}			
		}
		
	}
     return $this->render('IngenieriaUsuarioBundle:Default:portal.html.twig', array("error"=>array("message"=>"")));
    }


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
	// Registra y modifica un programa academico
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
			$d = $repository->findOneBy(array('nombre' => $director->getCi()));

			if ($p != NULL){
				throw $this->createNotFoundException('ERR_PROGRAMA_REGISTRADO');
			}

		   // Completar las propiedades que el usuario no rellena en el formulario

			$em->persist($d);

			//los roles fueron cargados de forma manual en la base de datos
			//buscamos una instancia role tipo coordinador 
			/*
			$codigo = 1; //codigo corresponde a coordinador		
			$repository = $this->getDoctrine()->getRepository('CituaoUsuarioBundle:Role');
			$role = $repository->findOneBy(array('id' => $codigo));

			if ($role == NULL){
				throw $this->createNotFoundException('ERR_ROLE_NO_ENCONTRADO');
			}
			$usuario = new Usuario();
			//cargamos todos los atributos al usuario
			$usuario->setUsername($programa->getCoordinador());
			$usuario->setPassword($formulario->get('password')->getData());
			$usuario->setSalt(md5(time()));
			$usuario->addRole($role);  //cargamos el rol al coordinador

			//codificamos el password			
			$encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
			$passwordCodificado = $encoder->encodePassword($usuario->getPassword(), $usuario->getSalt());
			$usuario->setPassword($passwordCodificado);
			$em->persist($usuario);
			*/

			$em->flush();
			return $this->redirect($this->generateUrl('usuario_adm_homepage'));
		}

		return $this->render('IngenieriaUsuarioBundle:Default:registrardirector.html.twig', array(
			'formulario' => $formulario->createView()
			));		

	}		
	
	
}
