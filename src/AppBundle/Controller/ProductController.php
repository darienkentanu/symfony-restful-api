<?php

namespace AppBundle\Controller;

// use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Product;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductController extends Controller{

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

    public function createFTAction(Request $request) 
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return new JsonResponse(array("status" => "success"));
        } 
        return new JsonResponse(array("messsage" => "an error has been occured"));
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
    public function deleteproductAction($productId){
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $product = $repository->find($productId);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$productId
            );
        }
        $entityManager->remove($product);
        $entityManager->flush();
        return new JsonResponse(array(
            "status" => 200,
            "message" => "product with id " . $productId . " successfully deleted"
        ));
    }
}
