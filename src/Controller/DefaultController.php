<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/admin")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/dashboard",name="dashboardadmin")
     */
    public function index(): Response
    {
        return $this->render('default/dashboard_admin.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    /**
     * @Route("/users")
     */
    public function usersAction(): Response
    {
        return $this->render('default/user_table-page.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/interventions",name="intervetion")
     */
    public function interventionAction(): Response
    {
        return $this->render('default/interventions_table-page.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }


}
