<?php

namespace AppBundle\Controller;

// use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
// use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Product;
use Symfony\Component\HttpFoundation\JsonResponse;

class ShowProductController extends Controller{

    public function showAction($productId)
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($productId);
    
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $productId
            );
        }

        $name = $product->getName();
        $price = $product->getPrice();
        $desc = $product->getDescription();

        $response = array(
            "id" => $product->getId(),
            "name" => $name,
            "price" => $price,
            "desc" => $desc,
        );
       
        return new JsonResponse($response);
        // return new JsonResponse(var_dump($product));
        // ... do something, like pass the $product object into a template
    }

    public function updateAction($productId)
{
    $entityManager = $this->getDoctrine()->getManager();
    $product = $entityManager->getRepository(Product::class)->find($productId);

    if (!$product) {
        throw $this->createNotFoundException(
            'No product found for id '.$productId
        );
    }

    $product->setName('New product name!');
    $entityManager->flush();

    // var_dump($product);
    // die();
    $response = array(
        "id" => $product->getId(),
        "name" => $product->getName(),
        "price" => $product->getPrice(),
        "desc" => $product->getDescription(),
    );
   
    return new JsonResponse($response);
    // return $this->redirectToRoute('homepage');
}
}
