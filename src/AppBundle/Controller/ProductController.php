<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Product;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Form\Type\ProductType;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller{

    // get product from db
    public function getAction($productId)
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

    // add product using input from form
    public function addAction(Request $request)
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
        if (!$request->request->has('note')) {
            return new JsonResponse('note must be filled', 400);
        }
        $name = $request->request->get('name');
        $price = $request->request->get('price');
        $description = $request->request->get('description');
        $note = $request->request->get('note');
        $product->setName($name);
        $product->setPrice($price);
        $product->setDescription($description);
        $product->setNote($note);

        $entityManager = $this->getDoctrine()->getManager();
        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
    }

    // add product using input from formType -> tidak perlu panggil form builder
    public function addWithFormTypeAction(Request $request) 
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

    // update product using id and json input
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
        if (!empty($content)) {
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

    // update product using id and query param
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

    // delete product by id
    public function deleteAction($productId){
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
