<?php

namespace PG\PlatformBundle\Controller;

use PG\PlatformBundle\Entity\Command;
use PG\PlatformBundle\Entity\Product;
use PG\PlatformBundle\Entity\ProductCommand;
use PG\PlatformBundle\Form\CommandType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CommandController extends Controller
{
    /**
     * @Security("has_role('ROLE_CLIENT')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
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

                        if ($quantity > 0) {
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

    /**
     * @Security("has_role('ROLE_CLIENT')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $commands = $em->getRepository(Command::class)->findWithProductsCommand($id);

        if (empty($commands)) {
            throw new NotFoundHttpException("La commande recherchée (" . $id . ") n'existe pas.");
        }

        $command = $commands[0];

        $currentUser = $this->getUser();
        // Someone tries to edit someone else's command
        if ($command->getClient() !== $currentUser) {
            $request->getSession()->getFlashBag()->add('error', 'Vous ne pouvez pas modifier cette commande.');
            return $this->redirectToRoute('pg_platform_order_add');
        }

        $products = $em->getRepository(Product::class)->findAllOrderedByName();

        $fakeCommand = new Command();
        $fakeCommand->setDate($command->getDate());
        $form = $this->createForm(CommandType::class, $fakeCommand);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                // update date
                $command->setDate($fakeCommand->getDate());

                $productQuantities = $request->request->get('command');

                foreach ($products as $product) {
                    $quantity = intval($productQuantities['products'][$product->getId()]['quantity']);

                    $productCommands = $command->getProducts();
                    $productFound = false;

                    foreach ($productCommands as $productCommand) {
                        // Product already into command
                        if ($productCommand->getProduct() === $product) {
                            $productFound = true;
                            // update quantity here
                            if ($quantity > 0) {
                                $productCommand->setQuantity($quantity);
                            } // remove it from command
                            else {
                                $command->removeProduct($productCommand);
                            }
                            break;
                        }
                    } // end productsCommands for loop

                    // create a new item if not updated/found
                    if (!$productFound && $quantity > 0) {
                        $productCommand = new ProductCommand();
                        $productCommand->setCommand($command);
                        $productCommand->setProduct($product);
                        $productCommand->setQuantity($quantity);
                        $command->addProduct($productCommand);
                    }
                } // end products for loop

                if ($command->getProducts()->isEmpty()) {
                    $request->getSession()->getFlashBag()->add('warning', 'Commande vide : annulation des modifications.
                    Vous pouvez supprimer une commande depuis l\'espace "Mes commandes".');
                } else {
                    $command->updatedAtDate();
                    $em->persist($command);
                    $em->flush();

                    $request->getSession()->getFlashBag()->add('notice', 'Votre commande a bien été mise à jour.');
                }

                return $this->redirectToRoute('pg_platform_order_edit', array('id' => $id));
            } // end isValid()
        }

        return $this->render('PGPlatformBundle:Command:edit.html.twig', array(
            'form' => $form->createView(),
            'products' => $products,
            'productCommands' => $command->getProducts(),
        ));
    }

    /**
     * @Security("has_role('ROLE_CLIENT')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $currentUser = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $commands = $em->getRepository(Command::class)->findByClient($currentUser->getId());

        return $this->render('PGPlatformBundle:Command:orders.html.twig', array(
            'commands' => $commands,
        ));
    }

    /**
     * @Security("has_role('ROLE_CLIENT')")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $commands = $em->getRepository(Command::class)->findWithProductsCommand($id);

        if (empty($commands)) {
            throw new NotFoundHttpException("La commande recherchée (" . $id . ") n'existe pas.");
        }

        $command = $commands[0];

        $currentUser = $this->getUser();
        // Someone tries to remove someone else's command
        if ($command->getClient() !== $currentUser) {
            $request->getSession()->getFlashBag()->add('error', 'Vous ne pouvez pas supprimer la commande # ' . $id);
            return $this->redirectToRoute('pg_platform_order_list');
        }

        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            foreach ($command->getProducts() as $productCommand) {
                $em->remove($productCommand);
            }

            $em->remove($command);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', "Commande supprimée");

            return $this->redirectToRoute('pg_platform_order_list');
        }

        return $this->render('PGPlatformBundle:Command:delete.html.twig', array(
            'command' => $command,
            'form' => $form->createView(),
        ));
    }
}
