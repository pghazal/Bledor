<?php

namespace PG\PlatformBundle\Controller;

use PG\PlatformBundle\Entity\Command;
use PG\PlatformBundle\Entity\Product;
use PG\PlatformBundle\Entity\ProductCommand;
use PG\PlatformBundle\Form\CommandType;
use PG\PlatformBundle\Form\ProductCommandType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommandController extends Controller
{
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository(Product::class)->findAllWithImage();

        $currentUser =  $this->getUser();

        $form = $this->createForm(CommandType::class);

        return $this->render('PGPlatformBundle:Command:add.html.twig', array(
            'form' => $form->createView(),
            'products' => $products,
        ));
    }
}
