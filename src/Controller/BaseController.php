<?php

/*
*/
namespace App\Controller;

date_default_timezone_set('UTC');

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;



/**
* 
*/
class BaseController extends Controller
{
    /**
    * @Route("/", name="homepage")
    */
    public function homepage(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'No puede ver esta pagina sin estar Registrado.');

        return $this->render('gestorindice.html.twig');
    }

    /**
    * @Route("/login", name="login")
    */
    public function login(Request $request, AuthenticationUtils $authUtils): Response
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
    * @Route("/logout", name="logout")
    */
    public function logout(): Response
    {
        
    }
}