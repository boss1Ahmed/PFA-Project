<?php


namespace AppBundle\Metier;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ObjectManager;
use AppBundle\Entity\User;

class ProfileMetier
{

    public function updateConducteurProfile(Request $request, ObjectManager $manager ,User $user){
        //
        $nom = $request->get("nom");
        $prenom = $request->get("prenom");
        $email = $request->get("email");
        //
        $user->setNom($nom)
            ->setPrenom($prenom)
            ->setEmail($email);
        $manager->persist($user);
        $manager->flush();

    }
}