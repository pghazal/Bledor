<?php

namespace PG\UserBundle\Controller;

use PG\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    /* /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction(Request $request, $id)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));

        if ($user === null) {
            throw new NotFoundHttpException("User (id = " . $id . ") n'existe pas.");
        }

        $form = $this->createForm(UserType::class, $user);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->get('submit')->isClicked()) {
                    $userManager->updateUser($user);
                    $request->getSession()->getFlashBag()->add('notice', 'Utilisateur enregistré');
                } else if ($form->get('cancel')->isClicked()) {
                    // no-op
                }

                return $this->redirectToRoute('pg_user_list');
            }
        }

        return $this->render('PGUserBundle:Admin:edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /* /**
      * @Security("has_role('ROLE_ADMIN')")
      */
    public function deleteAction(Request $request, $id)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));

        if ($user === null) {
            throw new NotFoundHttpException("User (id = " . $id . ") n'existe pas.");
        }

        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $userManager->deleteUser($user);

            $request->getSession()->getFlashBag()->add('info', "Utilisateur supprimé.");

            return $this->redirectToRoute('pg_user_list');
        }

        return $this->render('PGUserBundle:Admin:delete.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }
}
