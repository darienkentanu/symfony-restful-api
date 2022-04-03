<?php

namespace AppBundle\Controller;

// use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
    }

    public function updateAction(Request $request, $productId)
{
    // check if product with id ... exist
    $entityManager = $this->getDoctrine()->getManager();
    $product = $entityManager->getRepository(Product::class)->find($productId);
    if (!$product) {
        throw $this->createNotFoundException(
            'No product found for id '.$productId
        );
    }

    $input = array();
    $content = $request->getContent();
    if (!empty($content))
    {
        $input = json_decode($content, true);
    }


    $product->setName($input["name"]);
    $entityManager->flush();

    $response = array(
        "id" => $product->getId(),
        "name" => $product->getName(),
        "price" => $product->getPrice(),
        "desc" => $product->getDescription(),
    );
   
    return new JsonResponse($response);
}

public function updateWithQueryParamAction(Request $request, $productId)
{
    // check if product with id ... exist
    $entityManager = $this->getDoctrine()->getManager();
    $product = $entityManager->getRepository(Product::class)->find($productId);
    if (!$product) {
        throw $this->createNotFoundException(
            'No product found for id '.$productId
        );
    }

    $name = $request->query->get("name");

    $product->setName($name);
    $entityManager->flush();

    $response = array(
        "id" => $product->getId(),
        "name" => $product->getName(),
        "price" => $product->getPrice(),
        "desc" => $product->getDescription(),
    );
   
    return new JsonResponse($response);
}
}
