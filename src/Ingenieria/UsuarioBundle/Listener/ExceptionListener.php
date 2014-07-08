<?php

// src/Cituao/CoordBundle/EventListener/CituaoExceptionListener.php
namespace Ingenieria\UsuarioBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class ExceptionListener
{
	 protected $templating;
     protected $kernel;
		
		
	public function __construct(EngineInterface $templating, $kernel)
    {
        $this->templating = $templating;
        $this->kernel = $kernel;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
   
			// provide the better way to display a enhanced error page only in prod environment, if you want
            //if ('prod' == $this->kernel->getEnvironment()) {
					// exception object
			$exception = $event->getException();
			// new Response object
			$response = new Response();
			
			
			switch ($exception->getMessage()){
				case "ERR_DIRECTOR_REGISTRADO":
					$message = sprintf('Los datos ya estan registrados en el sistema!');
					$tmperror = 'IngenieriaUsuarioBundle:Default:error.html.twig';
					break;
				default:
					//$message = sprintf('Error no identificado!');
					$message = $exception->getMessage();
					$tmperror = 'IngenieriaUsuarioBundle:Default:error.html.twig';
					break;
				
			}
			
			$error = array('message' => $message);
			// set response content
			$response->setContent(
				// create you custom template AcmeFooBundle:Exception:exception.html.twig
				$this->templating->render(
					$tmperror,
					array('exception' => $error)
				)
			);
			
			
			// HttpExceptionInterface is a special type of exception
			// that holds status code and header details
			if ($exception instanceof HttpExceptionInterface) {
				$response->setStatusCode($exception->getStatusCode());
				$response->headers->replace($exception->getHeaders());
			} else {
				$response->setStatusCode(500);
			}

			// set the new $response object to the $event
			$event->setResponse($response);

	   // You get the exception object from the received event
		  /* 
		   $exception = $event->getException();
			$message = sprintf(
				'My Error says: %s with code: %s',
				$exception->getMessage(),
				$exception->getCode()
			);

			
			// Customize your response object to display the exception details
			$response = new Response();
			$response->setContent($message);
	  $response->setStatusCode($exception->getStatusCode());
		  

			// Send the modified response object to the event
			$event->setResponse($response);
			*/
		// }
	}
}

 
