<?php

namespace App\Controller;

use App\Entity\DateInteTech;
use App\Entity\PiecesofTache;
use App\Entity\User;
use App\Metier\InterventionMetier;
use App\Metier\TechnicienMetier;
use DateTime;
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
     * @Route("/dash/{id}",name="dashboard_tehcnicien",)
     *
     */
    public function dashboardAction(int $id = 0,Request $request,HubInterface $hub): Response
    {
        $now = date_create('now');
        $em = $this->getDoctrine()->getManager();

        $pieces = $em->getRepository('App:PieceRechange')->findAll();

        $technicien_metier = new TechnicienMetier();

        $mes_taches = $technicien_metier->getMesTaches($em, $this->getUser()->getId());

        if ($request->getMethod() == 'POST' && !$request->get("fin") && !$request->get("addpiece") ){
            //indiquer le debut de la tache
            $id_intervention = $request->get("id_intervention");
            $date_inte_tech = $em->getRepository("App:DateInteTech")->findOneBy(["intervention"=>$id_intervention,"technicien"=>$this->getUser()->getId()]);
            $intervention = $em->getRepository('App:Intervention')->findOneBy(["id"=>$id_intervention]);

            $date_inte_tech->setDateDebut($now)
                ->setNotified(true);
            $intervention->getDateInteTeches();
            $em->persist($date_inte_tech);
            $em->persist($intervention);
            $em->flush();

            $arrData = [

                'send'=>true

            ];

            return new JsonResponse($arrData);
        }

        if ($request->getMethod() == 'POST' && $request->get("fin") && !$request->get("addpiece")){
            //indiquer la fin de la tache
            $id_intervention = $request->get("id_intervention");

            $intervention = $em->getRepository('App:Intervention')->findOneBy(["id"=>$id_intervention]);
            $date_inte_tech_fin = $em->getRepository("App:DateInteTech")->findOneBy(["intervention"=>$id_intervention,"technicien"=>$this->getUser()->getId()]);


            if ($technicien_metier->testTechsTaches($intervention)) {
                $donnees = [
                    'id_cond'=>$intervention->getConducteur()->getId(),
                    'id_inter'=>$intervention->getId()
                ];
                $update = new Update("http://example.com/ping",json_encode($donnees),false);
                $hub->publish($update);
                $intervention->setEtat("T");
            }
            $date_inte_tech_fin->setDateFin($now);

            $em->persist($date_inte_tech_fin);
            $em->persist($intervention);
            $em->flush();

        }

        if ($id > 1){
            $task = $em->getRepository('App:DateInteTech')->findOneBy(['id'=>$id]);
            $task->setNotified(true);
            $em->persist($task);
            $em->flush();
        }
        if ($id == 1 ){
            foreach ($mes_taches as $tache){
                $tache->setNotified(true);
                $em->persist($tache);
                $em->flush();
            }
        }

        if ($request->getMethod() == 'POST' && $request->get("addpiece") && !$request->get("fin") ){
            $idtache = $request->get("id_tache");
            $libelle = $request->get("libelle");
            $quantite = $request->get("quantite");

            $tache = $em->getRepository('App:DateInteTech')->findOneBy(["id"=>$idtache]);
            $piece = $em->getRepository('App:PieceRechange')->findOneBy(["libelle"=>$libelle]);

            $usedpiece = new PiecesofTache();
            $usedpiece->setPieceRechange($piece)
                ->setQuantite($quantite)
                ->setTache($tache);
            dump($usedpiece);
            $em->persist($usedpiece);
            $em->flush();
        }

        return $this->render('interventions/intervention_techenicien.html.twig',[
            "taches"=>$mes_taches,
            "notifiedTask"=>$id,
            "pieces"=>$pieces
        ]);
    }


    /**
     * @Route("/interventions",name="intervention_affecté")
     */
    public function interventionsAction(): Response
    {
        $taches = $this->getDoctrine()->getRepository("App:DateInteTech")->findBy(["technicien"=>$this->getUser()]);
        $interventions =[];
        foreach ($taches as $tache){
            $interventions[] = $tache->getIntervention();
        }
        return $this->render('technicien/historique.html.twig',[
            "interventions"=>$interventions
        ]);
    }

    /**
     * @Route("/editprofile",name="technicien_profile")
     */
    public function profileAction(Request $request): Response
    {

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
            "base"=>"techenicien",
            "form"=>$form->createView()
        ]);
    }

    /**
     * @Route ("/tache/{id}",name="tache_information")
     */
    public function taskinfos(DateInteTech $task,Request $request){
        $em = $this->getDoctrine()->getManager();
        $pieces = $em->getRepository('App:PieceRechange')->findAll();
        if ($request->getMethod() == 'POST'){
            $libelle = $request->get("libelle");
            $quantite = $request->get("quantite");


            $piece = $em->getRepository('App:PieceRechange')->findOneBy(["libelle"=>$libelle]);

            $usedpiece = new PiecesofTache();
            $usedpiece->setPieceRechange($piece)
                ->setQuantite($quantite)
                ->setTache($task);
            $em->persist($usedpiece);
            $em->flush();
            $arrData = [
                'send'=>true
            ];

            return new JsonResponse($arrData);
        }

        return $this->render('technicien/task_infos.html.twig',[
            'task'=>$task,
            'pieces'=>$pieces
        ]);
    }



}
