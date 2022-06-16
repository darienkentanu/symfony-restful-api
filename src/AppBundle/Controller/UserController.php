<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\UserType;

class UserController extends Controller{

    public function addAction(Request $request) {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return new JsonResponse(array(
                "status" => 200,
                "message" => "success"
            ));
        }

        return $this->render('default/user.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}