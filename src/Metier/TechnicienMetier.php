<?php


namespace App\Metier;


use App\Entity\Intervention;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;


class TechnicienMetier
{
    public function getMesTaches(ObjectManager $em,Int $id){
        $taches = $em->getRepository("App:DateInteTech")->findBy(["technicien"=>$id, "dateFin"=>null]);

        return $taches;
    }

    public function testTechsTaches(Intervention $intervention){
        $taches = $intervention->getDateInteTeches();
        $i =0;
        foreach ($taches as $tache){
            if ($tache->getDateFin() == null){
                $i++;
            }
        }
        if ($i > 1){
            return false;
        }
        else return true;
    }

}