<?php

namespace PG\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function listAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();

        return $this->render('PGUserBundle:Admin:list.html.twig', array('users' =>
            $users
        ));
    }

}
