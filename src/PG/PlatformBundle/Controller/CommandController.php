<?php

namespace PG\PlatformBundle\Controller;

use PG\PlatformBundle\Entity\Command;
use PG\PlatformBundle\Form\CommandType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommandController extends Controller
{

    public function addAction(Request $request)
    {
        $command = new Command();

        $form = $this->createForm(CommandType::class, $command);

        return $this->render('PGPlatformBundle:Command:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
