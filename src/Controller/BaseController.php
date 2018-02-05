<?php

/*
*/
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
* 
*/
class BaseController extends Controller
{
    /**
    * @Route("/", name="homepage")
    */
    public function homepage()
    {
        return $this->render('gestorbase.html.twig');
    }
}