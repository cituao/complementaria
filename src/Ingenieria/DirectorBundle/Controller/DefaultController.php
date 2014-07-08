<?php

namespace Ingenieria\DirectorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('IngenieriaDirectorBundle:Default:index.html.twig');
    }
}
