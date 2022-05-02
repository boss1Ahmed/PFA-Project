<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Routing\Annotation\Route;

class DeniedAccessController extends AbstractController
{
    /**
     *
     * @Route ("/denied_access",name="denied_access")
     */
    public function indexAction(Request $request , HubInterface $hub)
    {
        $poste = $this->getUser()->getPoste();

        if ($poste == 'C') {
            //return $this->forward('App\Controller\ConducteurController::DashboardAction');
            return $this->redirectToRoute('conducteur_dashboard');
        }elseif ($poste == 'T'){
            //return $this->forward('App\Controller\TechnicienController::DashboardAction');
            return $this->redirectToRoute('dashboard_tehcnicien');
        }else{
            return $this->forward('FOSUserBundle:Security:login');
        }




    }
}
