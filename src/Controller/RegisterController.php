<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register1", name="app_register")
     */
    public function index(): Response
    {
        return $this->render('register/register-page.html.twig', [
            'controller_name' => 'RegisterController',
        ]);
    }
}
