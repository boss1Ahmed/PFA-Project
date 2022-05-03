<?php

namespace App\Controller;

use App\dto\InterventionTech;
use App\Entity\Defaillance;
use App\Entity\Intervention;
use App\Entity\Machine;
use App\Entity\TypeDefaillance;
use App\Entity\User;
use App\Metier\InterventionMetier;
use App\Metier\TechnicienMetier;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/admin")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/dashboard",name="affecter_intervention")
     */
    public function index(Request $request, HubInterface $hub): Response
    {
        $em = $this->getDoctrine()->getManager();
        $tech_metier = new TechnicienMetier();
        $intervention_metier = new InterventionMetier();
        $list = array();
        $i = 0;
        $interventions = $em->getRepository('App:Intervention')->findBy(["etat"=>"N"]);
        foreach ($interventions as $intervention){
            $id_type = $intervention->getDefaillance()->getTypeDefaillance()->getId();
            $suggestioni = $tech_metier->getAvailableTecnicien($id_type,$em);
            $group =  new InterventionTech($intervention,$suggestioni);
            $list[$i] = $group;
            $i++;
        }

        $techniciens = $em->getRepository('App:User')->findBy(['poste'=>'T']);

        if ($request->getMethod() == 'POST'){
            $chosenName = $request->get('technicien');
            $chosenTech = $em->getRepository('App:User')->findOneBy(["nom"=>$chosenName]);
            $intervention1 = $em->getRepository('App:Intervention')->findOneBy(['id'=>$request->get('intervention')]);

            $intervention_metier->affecter($chosenTech,$intervention1,$em);
            $intervention1->setEtat('E');
            $em->persist($intervention1);
            $em->flush();
            $intervention_metier->informTechnicians($intervention1,$chosenTech->getId(),$hub);
            $arrData = [
                'tech'=>$chosenTech->getNom()
            ];


            return new JsonResponse($arrData);
        }
        return $this->render('default/affectation_table-page.html.twig', [
            'interventions' => $interventions,
            'techniciens'=>$techniciens,
            'list'=>$list
        ]);
    }
    /**
     * @Route("/techs",name="lister_les_techniciens")
     */
    public function techsAction(): Response
    {
        $techs = $this->getDoctrine()->getRepository('App:User')->findBy(['poste'=>'T']);
        return $this->render('default/techniciens_table-page.html.twig', [
            'techs'=>$techs
        ]);
    }

    /**
     * @Route("/conducteurs",name="lister_les_conduceturs")
     */
    public function conducteursAction(): Response
    {
        $conds = $this->getDoctrine()->getRepository('App:User')->findBy(['poste'=>'C']);
        return $this->render('default/conducteurs_table-page.html.twig', [
            'conds'=>$conds
        ]);
    }

    /**
     * @Route("/interventions",name="lister_les_interventions")
     */
    public function interventionAction(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $interventions = $em->getRepository('App:Intervention')->findAll();
        return $this->render('default/interventions_table-page.html.twig', [
            'interventions'=>$interventions
        ]);
    }

    /**
     * @Route ("/intervention/{id}",name="show_intervention")
     */
    public function showInterventionAction(Intervention $intervention){
        return $this->render('default/show_intervention.html.twig',[
            'intervention'=>$intervention
        ]);
    }

    /**
     * @Route ("/delete/{id}",name="supprimer_intervention")
     * @return RedirectResponse
     */
    public function deleteInterventionAction(Intervention $intervention){
        $em = $this->getDoctrine()->getManager();
        $em->remove($intervention);
        $em->flush();
        return $this->redirectToRoute('lister_les_interventions');
    }

    /**
     * @Route ("/profile/{id}",name="administrer_profile")
     * @return Response
     */
    public function profileAction(User $user){
        return $this->render('default/profile_admin.html.twig',[
            'user'=>$user
        ]);
    }

    /**
     * @Route ("/machine/{id}",name="administrer_machine")
     * @return Response
     */
    public function machineAction(Machine $machine){

        return $this->render('default/show_machine.html.twig',[
            'machine'=>$machine
        ]);
    }

    /**
     * @Route ("/defaillance/{id}",name="administrer_defaillance")
     * @return Response
     */
    public function defaillanceAction(Defaillance $defaillance){
        return $this->render('default/show_defailance.html.twig',[
            'defaillance'=>$defaillance
        ]);
    }

    /**
     * @Route ("/type/{id}",name="administrer_type_defaillance")
     * @return Response
     */
    public function typeDefAction(TypeDefaillance $typeDefaillance){
        return $this->render('default/show_type_defaillance.html.twig',[
            'type'=>$typeDefaillance
        ]);
    }

    /**
     * @return Response
     * @Route ("/machines",name="lister_les_machines")
     */
    public function listeMachineAction(){
        $machines = $this->getDoctrine()->getRepository('App:Machine')->findAll();
        return $this->render('default/machines_table-page.html.twig',[
            'machines'=>$machines
        ]);
    }


    /**
     * @return Response
     * @Route ("/defaillances",name="lister_les_defaillances")
     */
    public function listeDefaillanceAction(){
        $defaillances = $this->getDoctrine()->getRepository('App:Defaillance')->findAll();
        return $this->render('default/defaillances_table-page.html.twig',[
            'defaillances'=>$defaillances
        ]);
    }

    /**
     * @return Response
     * @Route ("/typeDef",name="lister_les_types_defaillances")
     */
    public function listeTypeDefaillanceAction(){
        $Tdefaillances = $this->getDoctrine()->getRepository('App:TypeDefaillance')->findAll();
        return $this->render('default/type_def_table-page.html.twig',[
            'types'=>$Tdefaillances
        ]);
    }

    /**
     * @return Response
     * @Route ("/register",name="register_page")
     */
    public function registerAction(){
        return $this->render('register/register-page.html.twig');
    }
}
