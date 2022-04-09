<?php


namespace App\Metier;


use App\Entity\Defaillance;
use App\Entity\Intervention;
use App\Entity\Machine;
use App\Entity\TypeDefaillance;
use App\Entity\User;

use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class InterventionMetier
{

    public function createIntervention(Request $request , ObjectManager $em , User $user){

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
            ->setDateFin($now)
            ->setDescription("walo")
            ->setDefaillance($defaillance);
        $em->persist($intervention);
        $em->flush();
    }


}