<?php

namespace App\Controller;

use App\Entity\DateInteTech;
use App\Entity\Machine;
use App\Metier\InterventionMetier;
use App\Metier\TechnicienMetier;
use Doctrine\Common\Collections\Criteria;
use Dompdf\Dompdf;
use Dompdf\Options;
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
/*
        $path= $helper->asset($machine);*/
        /*
        $form = $this->createForm('App\Form\UserType',$this->getUser());
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $this->getDoctrine()->getManager()->persist($this->getUser());
            $this->getDoctrine()->getManager()->flush();
        }
        */

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'form'=>$form->createView(),


        ]);
    }

    /**
     * @Route ("/test2")
     */
    public function test(HubInterface $hub){
        return
        $this->render('test/test2.html.twig');


    }

    /**
     * @Route ("/testpdf/{id}",name="testpdf")
     */
    public function testpdf(DateInteTech $task){
        $now = date_create('now');

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($pdfOptions);

        $html = $this->renderView('test/pdffile.html.twig',[
            'tache'=>$task,
            'date'=>$now
        ]);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $dompdf->stream($now->format('Y-m-d:H-i').".pdf", [
            "Attachment" => true
        ]);
    }
}

