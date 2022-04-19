<?php


namespace App\Metier;


use App\Entity\Intervention;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
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


    public function getAvailableTecnicien(int $id_secteur, ObjectManager $manager)
    {
        //if the tasks list is fresh
        $technicians = $manager->getRepository("App:User")->findBy(["typeTech"=>$id_secteur]);
        $count  = 0;
        foreach ($technicians as $technician){
            if ($technician->getDateInteTeches()->getValues() == null){
                $chosenTech = $technician;
                $count++;
            }
        }
        if ($count != 0){
            dump("here");
            dump($chosenTech);
            return $chosenTech;
        }else {
            //if there is at least one available technician
            $criteria = new Criteria();
            $criteria->where(Criteria::expr()->neq('dateFin', null));
            $taches = $manager->getRepository("App:DateInteTech")->matching($criteria);
            //new method
            $nulltaches = $manager->getRepository("App:DateInteTech")->findBy(["dateFin" => null]);
            foreach ($nulltaches as $nulltache) {
                $id = $nulltache->getTechnicien()->getId();
                foreach ($taches as $tach) {
                    if ($tach->getTechnicien()->getId() == $id) {
                        $taches->removeElement($tach);
                    }
                }
            }
            //******************************
            //all free technicians are in $taches->getTechniciens

            if ($taches->getValues() != null) {
                //******************************
                //if there is an available technician
                dump("1");
                $chosenTech =$this->getTechnicien($taches->getValues(), $id_secteur);
                dump($chosenTech);
                return $chosenTech;
            } else {
                dump("2");
                //die();
                //******************************
                //if there is no available technician ??
                $taches = $manager->getRepository("App:DateInteTech")->findAll();
                $chosenTech =$this->getTechnicien($taches, $id_secteur);
                dump($chosenTech);
                return $chosenTech;
            }
        }
    }

    function getTechnicien(Array $taches, int $id_secteur){
        $date1 = new ArrayCollection();
        $techs = new ArrayCollection();
        $i=0;
        foreach ($taches as $tache){
            $tech = $tache->getTechnicien();
            if ($tech->getTypeTech()->getId() == $id_secteur) {
                $date1[$i] = $tache->getDateDebut();
                $techs[$i] = $tech;
                foreach ($tache->getTechnicien()->getDateInteTeches() as $dateInteTech) {
                    if ($date1[$i] < $dateInteTech->getDateDebut()) {
                        $date1[$i] = $dateInteTech->getDateDebut();
                    }
                }
            }
            $i++;
        }
        $j= 0;
        $index = $j;
        $min_date = $date1->getValues()[0];

        foreach ($date1 as $date){
            if ($min_date > $date){
                $min_date = $date;
                $index = $j;
            }
            $j++;
        }

        $chosenOne = $techs->getValues()[$index];

        return $chosenOne;
    }

}