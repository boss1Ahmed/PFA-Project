<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeniedAccessController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response|null
     * @Route ("/denied_access")
     */
    public function indexAction(Request $request)
    {
        $poste = $this->getUser()->getPoste();

        if ($poste == 'C') {
            return $this->forward('App\Controller\ConducteurController::DashboardAction');
        }elseif ($poste == 'T'){
            return $this->forward('App\Controller\TechnicienController::DashboardAction');
        }else{
            return $this->forward('FOSUserBundle:Security:login');
        }




    }
}
