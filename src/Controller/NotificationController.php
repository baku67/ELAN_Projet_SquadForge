<?php

namespace App\Controller;

use App\Entity\Candidature;
use App\Entity\Group;
use App\Entity\User;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function notifUpdateCandidature(string $case, User $user, Group $group): bool
    {
        $notification = new Notification();

        // Message de la notif selon $case
        if ($case == "accept") {
            $notification->setText("Votre candidature pour rejoindre la team \"" . $group->getTitle() . "\" a été acceptée.");
        }
        elseif ($case == "reject") {
            $notification->setText("Votre candidature pour rejoindre la team \"" . $group->getTitle() . "\" a été rejetée.");
        }

        $notification->setDateCreation(new \DateTime());
        $notification->setUser($user);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        return true;
    }

    public function notifNewLeader(User $user, Group $group): bool
    {
        $notification = new Notification();

        // Message de la notif au nouveau leader
        $notification->setText("Vous êtes désormais leader de la team \"" . $group->getTitle() . "\"");
        
        $notification->setDateCreation(new \DateTime());
        $notification->setUser($user);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        // Notifs aux autres membres (nouveau leader exclus)
        $members = $group->getMembers();

        foreach ($members as $member) {
            if($members != $user) {
                $notification2 = new Notification();

                // Message de la notif
                $notification2->setText("\"" . $user->getPseudo() . "\" est désormai leader de la team \"" . $group->getTitle() . "\"");
                
                $notification2->setDateCreation(new \DateTime());
                $notification2->setUser($member);
    
                $this->entityManager->persist($notification2);
                $this->entityManager->flush();
            }
        }

        return true;
    }

    
    public function notifMemberLeave(Group $group, User $user): bool
    {
        $members = $group->getMembers();

        foreach ($members as $member) {
            $notification = new Notification();

            // Message de la notif
            $notification->setText("\"" . $user->getPseudo() . "\" a quitté la team \"" . $group->getTitle() . "\"");
            
            $notification->setDateCreation(new \DateTime());
            $notification->setUser($member);

            $this->entityManager->persist($notification);
            $this->entityManager->flush();
        }
        
        return true;
    }


    public function notifKickedFromGroup(Group $group, User $user): bool
    {
        $notification = new Notification();

        // Message de la notif 
        $notification->setText("Vous avez été expulsé de la team \"" . $group->getTitle() . "\"");

        $notification->setDateCreation(new \DateTime());
        $notification->setUser($user);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        // Notifs aux autres membres
        $this->notifMemberLeave($group, $user);

        return true;
    }


    
    public function notifNewCandidature(User $leader, Candidature $candidature): bool
    {
        $notification = new Notification();

        // Message de la notif 
        $notification->setText("Nouvlle candidature de \"" . $candidature->getUser()->getPseudo() . "\" pour votre team \"" . $candidature->getGroupe()->getTitle() . "\"");

        $notification->setDateCreation(new \DateTime());
        $notification->setUser($leader);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        // Notifs aux autres membres
        $this->notifMemberLeave($group, $user);

        return true;
    }
}
