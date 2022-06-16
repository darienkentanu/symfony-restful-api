<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CobaController extends Controller{

    public function cobaAction(Request $request) {
        return new JsonResponse("SUCCESS", 200);
    }  
}