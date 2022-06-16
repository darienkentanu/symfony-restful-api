<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserController extends Controller{

    public function addAction() {
        $user = new User();
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder($user)
            ->add('name', TextType::class)
            ->add('age', IntegerType::class)
            ->add('save', SubmitType::class, array('label' => 'Add User'))
            ->getForm();
        return $this->render('default/user.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}