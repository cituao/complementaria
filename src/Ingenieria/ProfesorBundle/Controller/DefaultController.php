<?php

namespace Ingenieria\ProfesorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('IngenieriaProfesorBundle:Default:index.html.twig');
    }
}
