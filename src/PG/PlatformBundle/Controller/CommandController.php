<?php

namespace PG\PlatformBundle\Controller;

use PG\PlatformBundle\Entity\Command;
use PG\PlatformBundle\Entity\Product;
use PG\PlatformBundle\Entity\ProductCommand;
use PG\PlatformBundle\Form\CommandType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommandController extends Controller
{
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository(Product::class)->findAllWithImage();

        $command = new Command();
        $form = $this->createForm(CommandType::class, $command);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $productQuantities = $request->request->get('command');

                foreach ($products as $product) {
                    $productCommand = new ProductCommand();
                    $productCommand->setCommand($command);
                    $productCommand->setProduct($product);
                    $productCommand->setQuantity(intval($productQuantities['products'][$product->getId()]['quantity']));
                    $command->addProduct($productCommand);
                }

                $currentUser = $this->getUser();
                $command->setClient($currentUser);
            }
        }

        return $this->render('PGPlatformBundle:Command:add.html.twig', array(
            'form' => $form->createView(),
            'products' => $products,
        ));
    }
}
