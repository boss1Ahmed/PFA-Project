<?php

namespace App\Controller;

use App\Entity\DateInteTech;
use App\Metier\InterventionMetier;
use App\Metier\TechnicienMetier;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Hub;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use function MongoDB\BSON\toJSON;

/**
 * Class TechnicienController
 * @package App\Controller
 * @Route ("/technicien")
 * @Security ("user.getPoste() == 'T'")
 */
class TechnicienController extends AbstractController
{

    /**
     * @Route("/",name="dashboard_tehcnicien",)
     *
     */
    public function dashboardAction(Request $request,HubInterface $hub): Response
    {
        $now = date_create('now');
        $em = $this->getDoctrine()->getManager();

        $technicien_metier = new TechnicienMetier();

        $mes_taches = $technicien_metier->getMesTaches($em, $this->getUser()->getId());



        if ($request->getMethod() == 'POST' && !$request->get("fin")){
            //indiquer le debut de la tache
            $id_intervention = $request->get("id_intervention");
            $date_inte_tech = $em->getRepository("App:DateInteTech")->findOneBy(["intervention"=>$id_intervention,"technicien"=>$this->getUser()->getId()]);
            $intervention = $em->getRepository('App:Intervention')->findOneBy(["id"=>$id_intervention]);

            $date_inte_tech->setDateDebut($now);
            $intervention->getDateInteTeches();
            $em->persist($date_inte_tech);
            $em->persist($intervention);
            $em->flush();
        }

        if ($request->getMethod() == 'POST' && $request->get("fin")){
            //indiquer la fin de la tache
            $id_intervention = $request->get("id_intervention");

            $intervention = $em->getRepository('App:Intervention')->findOneBy(["id"=>$id_intervention]);
            $date_inte_tech_fin = $em->getRepository("App:DateInteTech")->findOneBy(["intervention"=>$id_intervention,"technicien"=>$this->getUser()->getId()]);


            if ($technicien_metier->testTechsTaches($intervention)) {
                $update = new Update("http://example.com/ping",$id_intervention,false);
                $hub->publish($update);
                $intervention->setEtat("T");
            }
            $date_inte_tech_fin->setDateFin($now);

            $em->persist($date_inte_tech_fin);
            $em->persist($intervention);
            $em->flush();

        }
        return $this->render('interventions/intervention_techenicien.html.twig',[
            "taches"=>$mes_taches
        ]);
    }


    /**
     * @Route("/interventions",name="intervention_affecté")
     */
    public function interventionsAction(): Response
    {
        $interventions = $this->getDoctrine()->getRepository("App:Intervention")->findAll();
        return $this->render('technicien/historique.html.twig',[
            "interventions"=>$interventions
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
