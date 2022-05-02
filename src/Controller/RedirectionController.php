<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Routing\Annotation\Route;

class RedirectionController extends AbstractController
{

    /**
     * @Route ("/check", name="access_checker")
     */
    public function accessAction(Request $request , HubInterface $hub){
        return $this->forward('App\Controller\DeniedAccessController::indexAction');

    }
}
