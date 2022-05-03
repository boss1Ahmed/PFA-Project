<?php

namespace App\Controller;

use App\Entity\Machine;
use App\Metier\TechnicienMetier;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use function Sodium\add;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="app_test")
     */
    public function index(Request $request,UploaderHelper $helper): Response
    {

        $machine = $this->getDoctrine()->getManager()->getRepository('App:Machine')->find(3);
        $form = $this->createForm('App\Form\MachineType',$machine);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->persist($machine);
            $this->getDoctrine()->getManager()->flush();
        }

        $path= $helper->asset($machine);

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'form'=>$form->createView(),
            'machine'=>$machine,
            'path'=>$path

        ]);
    }

    /**
     * @Route ("/test2")
     */
    public function test(){
        $machine = new Machine();
        $form = $this->createForm('App\Form\MachineType',$machine);
        if ($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->persist($machine);
            $this->getDoctrine()->getManager()->flush();
        }

    }
}

