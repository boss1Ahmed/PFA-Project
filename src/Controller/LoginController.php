<?php

namespace App\Controller;

use App\Entity\Defaillance;
use App\Entity\Machine;
use App\Entity\TypeDefaillance;
use AppBundle\Metier\InterventionMetier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    /**
     * @Route("/login1", name="app_login")
     */
    public function index(): Response
    {
        return $this->render('login/login2.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
    /**
     * @Route("/conducteurdashboard", name="app_login")
     */
    public function indexa(): Response
    {
        return $this->render('interventions/intervention_conducteur.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
    /**
     * @Route("
    /editprofile")
     */
    public function indexac(): Response
    {
        return $this->render('edit-profile/edit_profile-page.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
    /**
     * @Route("
    /profile")
     */
    public function indexact(): Response
    {
        return $this->render('edit-profile/profile_check-page.html.twig',);
    }




    /**
     * @Route("
    /interventionconducteur")
     */
    public function indexaction1(): Response
    {
        return $this->render('interventions/intervention_conducteur.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }


    /**
     * @Route ("/creerintervention1", name="creation_intervention1")
     */
    public function testAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $machines = $em->getRepository(Machine::class)->findAll();
        $defaillances = $em->getRepository(Defaillance::class)->findAll();
        $secteurs = $em->getRepository(TypeDefaillance::class)->findAll();

        //Creer l'intervention
        //cet attribut $request->get('machine_name') est utilisée pour differentié
        // entre la creation de l'intervention est la recherche des defaillances
        if ($request->getMethod() == 'POST' && !$request->get('machine_name')) {

            $metier = new InterventionMetier();
            $user = $this->getUser();
            $metier->createIntervention($request, $em, $user);

        }

        //Afficher les defaillances pour chaque machine slectionné
        if ($request->getMethod() == 'POST' && $request->get('machine_name') ){
            $machinN = $em->getRepository(Machine::class)->findOneBy(['nom_machine'=>$request->get('machine_name')]);
            $defsN = $machinN->getDefaillances();
            $noms = array();
            for ($i=0; $i<count($defsN); $i++){
                $noms[$i]= $defsN[$i]->getLibelle();
            }

            $arrData = [
                'noms'=>$noms,
                'nbr'=>count($defsN)
            ];

            return new JsonResponse($arrData);
        }

        return $this->render('interventions/creer_intervention.html.twig',[
            'machines'=>$machines,
            'defaillances'=>$defaillances,
            'secteurs'=>$secteurs,
        ]);
    }
}
