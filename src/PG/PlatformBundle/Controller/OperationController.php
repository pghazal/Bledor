<?php

namespace PG\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OperationController extends Controller {

    public function addAction(Request $request) {
        return new Response("OK");
    }
}
