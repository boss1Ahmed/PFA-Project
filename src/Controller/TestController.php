<?php

namespace App\Controller;

use App\Metier\TechnicienMetier;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use function Sodium\add;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="app_test")
     */
    public function index(): Response
    {

        $metier = new TechnicienMetier();
        $technicien = $metier->getAvailableTecnicien(2,$this->getDoctrine()->getManager());


        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',

        ]);
    }

    /**
     * @Route ("/test2")
     */
    public function test(HubInterface $hub){
        $donnees = [
            'id'=>9,
            'date'=>date('d-m-Y à H:i'),
            'nom'=>"walo",
            'def'=>"defaillance"
        ];
        $update = new Update("http://example.com/ping1",json_encode($donnees),false);
        $hub->publish($update);
        die("success");
    }
}

