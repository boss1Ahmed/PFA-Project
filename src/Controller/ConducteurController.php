<?php

namespace App\Controller;

use App\Entity\Defaillance;
use App\Entity\Machine;
use App\Entity\TypeDefaillance;
use App\Entity\User;
use App\Metier\InterventionMetier;

use App\Metier\TechnicienMetier;
use DateTime;
use Doctrine\Common\Collections\Criteria;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ConducteurController
 * @package App\Controller
 * @Route ("/conducteur")
 * @Security ("user.getPoste() == 'C'")
 */
class ConducteurController extends AbstractController
{
    /**
     * @Route("/", name="conducteur_dashboard")
     */
    public function dashboardAction(): Response
    {
        $criteria = new Criteria();
        $criteria->where(Criteria::expr()->neq('etat', "TV"));
        $interventions = $this->getDoctrine()->getRepository("App:Intervention")->matching($criteria);
        return $this->render('interventions/intervention_conducteur.html.twig',[
            "interventions"=>$interventions
        ]);
    }

    /**
     * @Route ("/changestatut",name="changer_etat_intervention")
     */
    public function changeAction(Request $request){
        $em=$this->getDoctrine()->getManager();
       $id = $request->get("key");
       $intervention = $em->getRepository("App:Intervention")->findOneBy(["id"=>$id]);
       $intervention->setEtat("TV");
       $em->persist($intervention);
       $em->flush();

        return new JsonResponse(null);
    }

    /**
     * @return Response
     * @Route ("/historique",name="historique_intervention_conducteur")
     */
    public function interventionListeAction(){
        $interventions = $this->getDoctrine()->getRepository("App:Intervention")->findAll();
        return $this->render('conducteur/intervention_Historique_C.html.twig',[
            "interventions"=>$interventions
        ]);
    }
    /**
     * @Route("/editprofile",name="conducteur_profile")
     */
    public function profileAction(Request $request): Response
    {
        $new = new User();
        $form = $this->createForm('App\Form\UserType',$this->getUser());
        $form->handleRequest($request);
        if($request->getMethod()=='POST' && $form->isSubmitted() && $form->isValid()){
            $firstname=$request->get('nom');
            $lastname=$request->get('prenom');
            $mail=$request->get('mail');
            $addresse=$request->get('addresse');
            $tel=$request->get('tel');
            $birthday=$request->get('birthday');
            $date = date('Y-m-d',strtotime($birthday));
            $test = DateTime::createFromFormat('Y-m-d', $date);
            $user=$this->getUser();
            $user->setNom($firstname);
            $user->setPrenom($lastname);
            $user->setEmail($mail);
            $user->setTele($tel);
            $user->setAddresse($addresse);
            $user->setDateDeNaissance($test);
            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

        }

        return $this->render('edit-profile/edit_profile-page.html.twig', [
            "base"=>"conducteur",
            "form"=>$form->createView()
        ]);
    }

    /**
     * @Route ("/creerintervention", name="creation_intervention")
     *
     */
    public function createInterventionAction(Request $request , HubInterface $hub)
    {
        $em = $this->getDoctrine()->getManager();
        $machines = $em->getRepository(Machine::class)->findAll();
        $defaillances = $em->getRepository(Defaillance::class)->findAll();
        $secteurs = $em->getRepository(TypeDefaillance::class)->findAll();

        //Creer l'intervention
        //cet attribut $request->get('machine_name') est utilisée pour differentié
        //entre la creation de l'intervention est la recherche des defaillances
        if ($request->getMethod() == 'POST' && !$request->get('machine_name') && !$request->get('zone_name')) {
            $nom_secteur = $request->get('secteur');
            $secteur = $em->getRepository("App:TypeDefaillance")->findOneBy(["nom"=>$nom_secteur]);

            $metier = new InterventionMetier();
            $tech_metier = new TechnicienMetier();
            $technicien = $tech_metier->getAvailableTecnicien($secteur->getId(),$em);
            $user = $this->getUser();
            $metier->createIntervention($request, $em, $user,$technicien,$hub);

        }

        //Afficher les zones pour chaque machine selectionnée
        if ($request->getMethod() == 'POST' && $request->get('machine_name') ){
            $machinN = $em->getRepository(Machine::class)->findOneBy(['nom_machine'=>$request->get('machine_name')]);

            $zones = $machinN->getZones();
            $noms_zone = array();

            for ($i=0; $i<count($zones); $i++){
                $noms_zone[$i]= $zones[$i]->getLibelle();
            }

            $arrData = [

                'nomszones'=>$noms_zone,
                'nbrzones'=>count($zones),

            ];

            return new JsonResponse($arrData);
        }
        //afficher les defaillances pour chaque zone selectionnée
        if ($request->getMethod() == 'POST' && $request->get('zone_name')){
            $zone = $em->getRepository('App:Zone')->findOneBy(['libelle'=> $request->get('zone_name')]);
            $defs = $zone->getDefaillances();
            $noms = array();
            for ($i=0; $i<count($defs); $i++){
                $noms[$i]= $defs[$i]->getLibelle();
            }
            $arrData = [
                'noms'=>$noms,
                'nbr'=>count($defs)
            ];

            return new JsonResponse($arrData);

        }

        return $this->render('interventions/creer_intervention.html.twig',[
            'machines'=>$machines,
            'defaillances'=>$defaillances,
            'secteurs'=>$secteurs,
        ]);
    }


    /**
     * @Route ("/test",name="test_route")
     */
    public function testAction(Request $request){
        $defs = $this->getDoctrine()->getManager()->getRepository('App:Defaillance')->findAll();
        $nbr = count($defs);
        if ($request->getMethod() == 'POST'){
            $arrdata = [
                'nbr'=>$nbr
            ];
            return new JsonResponse($arrdata);
        }else{
            die("walo");
        }

    }


}
