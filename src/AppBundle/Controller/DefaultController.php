<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ));
    }

    public function createAction(Request $request)
    {
        $product = new Product();

        if (!$request->request->has('name')) {
            return new JsonResponse("name must be filled", 400);
        }
        if (!$request->request->has('price')) {
            return new JsonResponse("price must be filled", 400);
        }
        if (!$request->request->has('description')) {
            return new JsonResponse('description must be filled', 400);
        }


        $name = $request->request->get('name');
        $price = $request->request->get('price');
        $description = $request->request->get('description');
        $product->setName($name);
        $product->setPrice($price);
        $product->setDescription($description);

        $entityManager = $this->getDoctrine()->getManager();

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
    }
// {
//     $product = new Product();
//     $product->setName('Keyboard');
//     $product->setPrice(19.99);
//     $product->setDescription('Ergonomic and stylish!');

//     $entityManager = $this->getDoctrine()->getManager();

//     // tells Doctrine you want to (eventually) save the Product (no queries yet)
//     $entityManager->persist($product);

//     // actually executes the queries (i.e. the INSERT query)
//     $entityManager->flush();

//     return new Response('Saved new product with id '.$product->getId());
// }
}
