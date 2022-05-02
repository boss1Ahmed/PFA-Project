<?php


namespace App\Metier;


use App\Entity\DateInteTech;
use App\Entity\Defaillance;
use App\Entity\Intervention;
use App\Entity\Machine;
use App\Entity\TypeDefaillance;
use App\Entity\User;

use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class InterventionMetier
{

    public function createIntervention(Request $request , ObjectManager $em , User $user , User $technicien , HubInterface $hub){

        //date prévu
        $date_fin = date('Y-m-d h:i:s', strtotime($request->get('date_fin'))) ;

        //urgence (propriété urgence de l'intervention)
        if($request->get('urgence') == 'non'){
            $urgence = false;
        } else if($request->get('urgence') == 'oui')  {
            $urgence = true;
        };

        //nom de le machine (la relation entre intervention et machine )
        $nomMachine = $request->get('machine');
        $machie = $em->getRepository(Machine::class)->findOneBy(['nom_machine'=>$nomMachine]);

        //nom de la defaillance (la relation entre intervention et defaillance)
        $nom_defaillance = $request->get('defaillance');
        $defaillance =  $em->getRepository(Defaillance::class)->findOneBy(['libelle'=>$nom_defaillance]);

        //nom du secteur (type de defaillance )
        $nom_secteur = $request->get('secteur');
        $secteur = $em->getRepository(TypeDefaillance::class)->findOneBy(['nom'=>$nom_secteur]);

        //si le conducteur a saisi une nouvelle defaillance
        if($defaillance == null){
            $defaillance = new Defaillance();
            $defaillance->setLibelle($nom_defaillance)
                ->setTypeDefaillance($secteur);
            $em->persist($defaillance);
            $machie->getDefaillances()->add($defaillance);
            $em->persist($machie);
        }

        //creation d'une nouvelle intervention
        $intervention = new Intervention();
        $now = date_create('now');
        $intervention
            ->setConducteur($user)
            ->setDateLancement($now)
            ->setMachine($machie)
            ->setDateFin(null)
            ->setEtat("E")
            ->setDescription("")
            ->setDefaillance($defaillance)
            ->setUrgence($urgence);
        $em->persist($intervention);

        //affectation du technicien
        $tache = new DateInteTech();
        $tache->setIntervention($intervention)
            ->setTechnicien($technicien);
        $em->persist($tache);

        $em->flush();
        $this->informTechnicians($intervention,$hub);
    }

    public function informTechnicians(Intervention $intervention,HubInterface $hub){
        $donnees = [
            'id'=>$intervention->getId(),
            'date'=>$intervention->getDateLancement()->format('d-m-Y à H:i'),
            'nom'=>$intervention->getMachine()->getNomMachine(),
            'def'=>$intervention->getDefaillance()->getLibelle()
        ];
        $update = new Update("http://example.com/ping1",json_encode($donnees),false);
        $hub->publish($update);
    }


}