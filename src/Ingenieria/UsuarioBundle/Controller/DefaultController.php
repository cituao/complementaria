<?php

namespace Ingenieria\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Ingenieria\UsuarioBundle\Entity\Director;

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
	
}
