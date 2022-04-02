<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TechnicienController
 * @package App\Controller
 * @Route ("/technicien")
 * @Security ("user.getPoste() == 'T'")
 */
class TechnicienController extends AbstractController
{
    /**
     * @Route("/",name="dashboard_tehcnicien")
     */
    public function dashboardAction(): Response
    {
        return $this->render('interventions/intervention_techenicien.html.twig' );
    }

    /**
     * @Route("/interventions",name="intervention_affecté")
     */
    public function interventionsAction(): Response
    {
        return $this->render('interventions/intervention_techenicien.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }

    /**
     * @Route("/editprofile",name="technicien_profile")
     */
    public function profileAction(): Response
    {
        return $this->render('edit-profile/edit_profile-page.html.twig', [
            "base"=>"techenicien"
        ]);
    }
}
