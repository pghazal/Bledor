<?php

namespace PG\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use PG\PlatformBundle\Entity\Product;
use PG\PlatformBundle\Form\ProductType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
    public function productsAction($id = 0)
    {
        $em = $this->getDoctrine()->getManager();

        $products = null;

        if ($id < 1) {
            $products = $em->getRepository('PGPlatformBundle:Product')->findAllWithImage();
        } else {
            $products = $em->getRepository('PGPlatformBundle:Product')->findWithImage($id);
        }

        return $this->render('PGPlatformBundle:Product:products.html.twig', array(
            'products' => $products
        ));
    }

    /**
     * @Security("has_role('ROLE_GERANT')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($product);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Produit bien enregistrée.');

                return $this->redirectToRoute('pg_platform_products', array('id' => $product->getId()));
            }
        }

        return $this->render('PGPlatformBundle:Product:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Security("has_role('ROLE_GERANT')")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        $product = $this->getDoctrine()
            ->getManager()
            ->getRepository('PGPlatformBundle:Product')
            ->find($id);

        if (null === $product) {
            throw new NotFoundHttpException("Le produit d'id " . $id . " n'existe pas.");
        }

        $form = $this->createForm(ProductType::class, $product);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($product);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Produit bien modifié.');

                return $this->redirectToRoute('pg_platform_products', array('id' => $product->getId()));
            }
        }

        return $this->render('PGPlatformBundle:Product:edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Security("has_role('ROLE_GERANT')")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('PGPlatformBundle:Product')->find($id);

        if (null === $product) {
            throw new NotFoundHttpException("Le produit d'id " . $id . " n'existe pas.");
        }

        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->remove($product);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée.");

            return $this->redirectToRoute('pg_platform_products');
        }

        return $this->render('PGPlatformBundle:Product:delete.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
        ));
    }
}
