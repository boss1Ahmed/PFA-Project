<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/tables")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/table")
     */
    public function index(): Response
    {
        return $this->render('default/datetable.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    /**
     * @Route("/users")
     */
    public function tableAction(): Response
    {
        return $this->render('default/user_table-page.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    /**
     * @Route("/affectation")
     */
    public function affectationAction(): Response
    {
        return $this->render('default/affectation_table-page.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }


}
