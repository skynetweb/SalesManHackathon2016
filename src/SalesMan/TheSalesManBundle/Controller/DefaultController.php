<?php

namespace SalesMan\TheSalesManBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SalesManBundle:Default:index.html.twig');
    }
}
