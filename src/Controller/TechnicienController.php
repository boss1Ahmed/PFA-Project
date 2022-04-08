<?php

namespace App\Controller;

use App\Entity\DateInteTech;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function dashboardAction(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->getMethod() == 'POST'){
//indiquer le debut de la tache
            $date_inte_tech = new DateInteTech();
            $now = date_create('now');
            $intervention = $em->getRepository('App:Intervention')->findOneBy(["id"=>$request->get("id_intervention")]);
            $date_inte_tech->setTechnicien($this->getUser())
                ->setIntervention($intervention)
                ->setDateDebut($now);
            $intervention->getDateInteTeches()->add($date_inte_tech);
            $em->persist($date_inte_tech);
            $em->persist($intervention);
            $em->flush();

        }

        if ($request->get("fin")){
            //indiquer la fin de la tache
        }
        return $this->render('interventions/intervention_techenicien.html.twig');
    }


    /**
     * @Route("/interventions",name="intervention_affecté")
     */
    public function interventionsAction(): Response
    {
        return $this->render('technicien/historique.html.twig', [
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
