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
use App\Repository\InterventionRepository;
use DateTime;
use Doctrine\Common\Collections\Criteria;
use PhpParser\Node\Expr\Cast\String_;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * @Route ("/admin")
 * @Security ("user.getPoste() == 'A'")
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

            $id = $intervention_metier->affecter($chosenTech,$intervention1,$em);
            $intervention1->setEtat('E');
            $em->persist($intervention1);
            $em->flush();
            $intervention_metier->informTechnicians($intervention1,$chosenTech->getId(),$hub,$id);
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
    public function profileuserAction(User $user, Request $request){

        $form = $this->createForm('App\Form\UserType',$user);
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

            $user->setNom($firstname);
            $user->setPrenom($lastname);
            $user->setEmail($mail);

            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            dump($user);

        }

        return $this->render('default/profile_admin.html.twig',[
            'user'=>$user,
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route ("/machine/{id}",name="administrer_machine")
     * @return Response
     */
    public function machineAction(Machine $machine,Request $request){

        $form = $this->createForm('App\Form\MachineType',$machine);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->persist($machine);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->render('default/show_machine.html.twig',[
            'machine'=>$machine,
            'form'=>$form->createView()
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
     * @Route ("/machinecharts",name="machinechart_page")
     */
    public function chartsAction(InterventionRepository $interventionRepository){

        $machines = $this->getDoctrine()->getRepository('App:Machine')->findAll();
        $mois = ['1','2','3','4','5','6','7','8','9','10','11','12'];

        $machinesnom=[];

        foreach ($machines as $machine)
        {
            $nombreintervention=[];
            foreach ($mois as $month){
                $i=0;

                foreach ($machine->getInterventions() as $intervention){
                    if(substr($intervention->getDateLancement()->format('Y-m-d'),6,1)==$month)
                    {
                        $i++;
                    }
                }
                $nombreintervention[]=(int)$i;
            }
            $machinesnom[]=$machine->getNomMachine();
            $listeDeNombreIntervention[]=$nombreintervention;
        }

        return $this->render('charts/machineChatPage.html.twig',['nommachines'=>$machinesnom,'nbrinter'=>$listeDeNombreIntervention,'machines'=>$machines]);
    }
    /**
     * @return Response
     * @Route ("/techniciencharts",name="techchart_page")
     */
    public function techchartsAction(Request $request){
        $tachesinclueperiode=[];
        $counttache=[];
        $datestache=[];
        $nomstechs= [];
        $countall = [];
        $dateseach = [];

        //new
        $alldates = [];
        $alldates1 = [];
        $allcount = [];
        $techsname = [];
        $techniciennom = null;
        $techniciens = $this->getDoctrine()->getRepository('App:User')->findBy(["poste"=>'T']);


        if($request->getMethod()=='POST'){
            $i=0;
            $datedebut=$request->get('datedebut');
            $datefin=$request->get('datefin');
            $techniciennom = $request->get('technicienlistes');

            if ($techniciennom != "Touts les techniciens"){
                //for one technician
                $prenom=explode(" ",$techniciennom);
                $technicienobjet = $this->getDoctrine()->getRepository('App:User')->findOneBy(["nom"=>$prenom[1],"prenom"=>$prenom[0]]);
                $taches=$this->getDoctrine()->getRepository('App:DateInteTech')->findBy(["technicien"=>$technicienobjet]);

                foreach ($taches as $tache){
                    if($tache->getDateDebut()->format('Y-m-d') >= $datedebut && $tache->getDateDebut()->format('Y-m-d')<=$datefin ){
                        $tachesinclueperiode[]= $tache;
                        $datestache[] = $tache->getDateDebut()->format('Y-m-d');
                    }
                }
                foreach (array_unique($datestache) as $uniquedate ){
                    $i=0;
                    foreach ($tachesinclueperiode as $tachePeriode)
                    {
                        if($tachePeriode->getDateDebut()->format('Y-m-d')===$uniquedate){
                            $i++;
                        }
                    }
                    $counttache[]=(int)$i;
                }
            }else{
                //for all technicians

                $alltaches = $this->getDoctrine()->getRepository('App:DateInteTech')->findAll();
                $alltechs = $this->getDoctrine()->getRepository('App:User')->findBy(["poste"=>'T']);
                foreach ($alltaches as $task){
                    if ($task->getDateDebut()->format('Y-m-d') >= $datedebut && $task->getDateDebut()->format('Y-m-d')<=$datefin ){
                        $alldates[] = $task->getDateDebut()->format('Y-m-d');
                    }
                }

                $alldates = array_unique($alldates);
                foreach ($alldates as $date){
                    $alldates1[]= $date;
                }
                $alldates = $alldates1;
                dump($alldates);
                dump($alldates1);

                foreach ($alltechs as $tech){
                    $techsname[] =$tech->getNom();
                }
                foreach ($alltechs as $tech){
                    $taskperdate =[];
                foreach ($alldates as $date){


                        $i = 0;
                        $techtasks = $tech->getDateInteTeches();
                        foreach ($techtasks as $task){
                            if ($task->getDateDebut()->format('Y-m-d') == $date ){
                                $i++;
                            }
                        }
                        $taskperdate[]= $i;
                    }


                    $allcount[] = $taskperdate;
                }
                dump($techsname);
                dump($allcount);


                /*
                foreach ($techniciens as $tech){
                    $nomstechs[] = $tech->getNom();
                    $tachesPeach = [];
                    $tachesP = $tech->getDateInteTeches();
                    $counteach = [];

                    foreach ($tachesP as $tache1){
                        if($tache1->getDateDebut()->format('Y-m-d') >= $datedebut && $tache1->getDateDebut()->format('Y-m-d')<=$datefin ){
                            $tachesPeach[]= $tache1;
                            $dateseach[] = $tache1->getDateDebut()->format('Y-m-d');
                        }
                    }

                    dump(array_unique($dateseach));
                    foreach (array_unique($dateseach) as $uniquedate ){
                        $i=0;
                        foreach ($tachesPeach as $tachePeach)
                        {
                            if($tachePeach->getDateDebut()->format('Y-m-d')===$uniquedate){
                                $i++;
                            }
                        }
                        $counteach[]=(int)$i;
                    }
                    //dump($counteach);
                    $countall[] = $counteach;

                }
                */

            }

        }
        //return $this->render('charts/technicienChartPage.html.twig',["techniciens"=>$techniciens,"nombreinter"=>$counttache,'lesdates'=>array_unique($datestache),'tech'=>$techniciennom, 'techs'=>$nomstechs , 'countall'=>$countall, 'datesall'=>array_unique($dateseach)]);

        return $this->render('charts/technicienChartPage.html.twig',[
            "techniciens"=>$techniciens,
                "nombreinter"=>$counttache,
                'lesdates'=>array_unique($datestache),
                'tech'=>$techniciennom,
                'techs'=>$techsname ,
                'countall'=>$allcount,
                'datesall'=>$alldates,
                ]
        );
    }

    /**
     * @return Response
     * @Route ("/register",name="register_page")
     */
    public function registerAction(Request $request,EncoderFactoryInterface $encoderFactory){
        $user = new User();
        if ($request->getMethod() == 'POST') {
            $user->setEmail($request->get('email'))
                ->setPrenom($request->get('prenom'))
                ->setNom($request->get('nom'))
                ->setUsername($request->get('nom'))
                ->setPlainPassword($request->get('password'))
                ->setTele($request->get('tel'))
                ->setEnabled(true);
            $plainPassword = $user->getPlainPassword();
            $encoder = $encoderFactory->getEncoder($user);
            $salt = rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '=');
            $user->setSalt($salt);
            $hashedPassword = $encoder->encodePassword($plainPassword, $user->getSalt());
            $user->setPassword($hashedPassword);

            if ($request->get('poste') == 'T') {
                $secteur = $this->getDoctrine()->getRepository('App:TypeDefaillance')->findOneBy(['nom' => $request->get('secteur')]);
                $user->setPoste('T')
                    ->setTypeTech($secteur);

                dump($user);
            } else if ($request->get('poste') == 'C') {
                $user->setPoste('C');
                dump($user);
            }

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
        }


        $Tdefaillances = $this->getDoctrine()->getRepository('App:TypeDefaillance')->findAll();
        return $this->render('register/register-page.html.twig',[
            'secteurs'=>$Tdefaillances
        ]);
    }


    /**
     * @Route("/editprofile",name="admin_profile")
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
            "base"=>"techenicien",
            "form"=>$form->createView()
        ]);
    }

}