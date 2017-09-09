<?php

namespace PG\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends BaseController
{
    public function loginAction(Request $request)
    {
        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');

        if ($authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return new RedirectResponse($router->generate('index'), 307);
        }

        return parent::loginAction($request);
    }
}
