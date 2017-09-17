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
    private const LIMIT_COMMAND_UNIT = 10000;

    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository(Product::class)->findAllOrderedByName();

        $command = new Command();
        $form = $this->createForm(CommandType::class, $command);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $currentUser = $this->getUser();
                $existingCommand = $em->getRepository(Command::class)->findByClientAndDate($currentUser, $command->getDate());

                if (empty($existingCommand)) {
                    $command->setClient($currentUser);
                    $productQuantities = $request->request->get('command');

                    foreach ($products as $product) {
                        $quantity = intval($productQuantities['products'][$product->getId()]['quantity']);

                        if ($quantity > 0 && $quantity < self::LIMIT_COMMAND_UNIT) {
                            $productCommand = new ProductCommand();
                            $productCommand->setCommand($command);
                            $productCommand->setProduct($product);
                            $productCommand->setQuantity($quantity);
                            $command->addProduct($productCommand);
                        }
                    }

                    if ($command->getProducts()->isEmpty()) {
                        $request->getSession()->getFlashBag()->add('warning', 'Votre commande semble vide.');
                    } else {
                        $em->persist($command);
                        $em->flush();

                        $request->getSession()->getFlashBag()->add('notice', 'Votre commande a bien été prise en compte.');
                    }

                    return $this->redirectToRoute('pg_platform_order_add');

                } else {
                    $request->getSession()->getFlashBag()->add('warning', 'Une commande existe déjà pour cette date.');

                    return $this->redirectToRoute('pg_platform_order_add');
                    // Alert message : command exist
                    // Edit it ?
                    // New order ?
                }
            }
        }

        return $this->render('PGPlatformBundle:Command:add.html.twig', array(
            'form' => $form->createView(),
            'products' => $products,
        ));
    }
}
