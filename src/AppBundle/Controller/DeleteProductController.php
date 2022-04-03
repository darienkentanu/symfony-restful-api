<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Product;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteProductController extends Controller{

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