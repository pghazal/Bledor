<?php

namespace PG\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    public function indexAction() {
        return $this->render('PGPlatformBundle:Default:index.html.twig');
    }
}
