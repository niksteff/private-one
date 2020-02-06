<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @Route("/hello/{name}")
     */
    public function index($name)
    {
        return new Response("Hello $name!");
        return $this->render('default/index.html.twig', [
            'name' => $name,
        ]);
    }
}
