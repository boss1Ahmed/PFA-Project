<?php


namespace App\dto;


use App\Entity\Intervention;
use App\Entity\User;

class InterventionTech
{

    /**
     * @var Intervention
     */
    private $intervention;

    /**
     * @var User
     */
    private $technicien;

    /**
     * InterventionTech constructor.
     * @param Intervention $intervention
     * @param User $technicien
     */
    public function __construct(Intervention $intervention, User $technicien)
    {
        $this->intervention = $intervention;
        $this->technicien = $technicien;
    }

    /**
     * @return Intervention
     */
    public function getIntervention(): Intervention
    {
        return $this->intervention;
    }

    /**
     * @param Intervention $intervention
     */
    public function setIntervention(Intervention $intervention): void
    {
        $this->intervention = $intervention;
    }

    /**
     * @return User
     */
    public function getTechnicien(): User
    {
        return $this->technicien;
    }

    /**
     * @param User $technicien
     */
    public function setTechnicien(User $technicien): void
    {
        $this->technicien = $technicien;
    }


}