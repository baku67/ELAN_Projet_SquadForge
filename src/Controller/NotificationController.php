<?php

namespace App\Controller;

use App\Entity\Candidature;
use App\Entity\Media;
use App\Entity\Group;
use App\Entity\Topic;
use App\Entity\User;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{


    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    // Page de listing notifications User connecté
    #[Route('/showNotifsList', name: 'app_showNotifsList')]
    public function showNotifsList(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();
        $notifs = $user->getNotifications();

        // Toutes les notifs passent en "seen" (pas de /notifDetails)
        foreach ($notifs as $notif) {
            $notif->setSeen(true);
            $entityManager->persist($notif);
        }
        $entityManager->flush();

        return $this->render('user/notifsList.html.twig', [
            'user' => $user,
            'notifs' => $notifs,
        ]);
    }




    // **************************************************************************************************************************
    // Fonctions "d'envoi" de notifications
    // **************************************************************************************************************************


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


    public function notifNewLeader(User $newLeader, Group $group): bool
    {
        $notification = new Notification();

        // Message de la notif au nouveau leader
        $notification->setText("Vous êtes désormais leader de la team \"" . $group->getTitle() . "\"");
        
        $notification->setDateCreation(new \DateTime());
        $notification->setUser($newLeader);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        // Notifs aux autres membres (nouveau leader exclus)
        $members = $group->getMembers();

        foreach ($members as $member) {
            if($members != $newLeader) {
                $notification2 = new Notification();

                // Message de la notif
                $notification2->setText("\"" . $newLeader->getPseudo() . "\" est désormai leader de la team \"" . $group->getTitle() . "\"");
                
                $notification2->setDateCreation(new \DateTime());
                $notification2->setUser($member);
    
                $this->entityManager->persist($notification2);
                $this->entityManager->flush();
            }
        }
        return true;
    }

    

    public function notifMemberLeave(Group $group, User $leavingUser): bool
    {
        $members = $group->getMembers();

        foreach ($members as $member) {
            $notification = new Notification();

            // Message de la notif
            $notification->setText("\"" . $leavingUser->getPseudo() . "\" a quitté la team \"" . $group->getTitle() . "\"");
            
            $notification->setDateCreation(new \DateTime());
            $notification->setUser($member);

            $this->entityManager->persist($notification);
            $this->entityManager->flush();
        }
        return true;
    }



    public function notifKickedFromGroup(Group $group, User $userKicked): bool
    {
        $notification = new Notification();

        // Message de la notif 
        $notification->setText("Vous avez été expulsé de la team \"" . $group->getTitle() . "\"");

        $notification->setDateCreation(new \DateTime());
        $notification->setUser($userKicked);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        // Notifs aux autres membres
        $this->notifMemberLeave($group, $userKicked);

        return true;
    }


    

    public function notifNewCandidature(User $leader, Candidature $candidature): bool
    {
        $notification = new Notification();

        // Message de la notif 
        $notification->setText("Nouvelle candidature de \"" . $candidature->getUser()->getPseudo() . "\" pour votre team \"" . $candidature->getGroupe()->getTitle() . "\"");

        $notification->setDateCreation(new \DateTime());
        $notification->setUser($leader);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        return true;
    }




    public function notifValidatedMedia(User $author, Media $media): bool 
    {
        $notification = new Notification();

        $notification->setText("Votre média \"" . $media->getTitle() . "\" a été approuvé par la modération");

        $notification->setDateCreation(new \DateTime());
        $notification->setUser($author);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        return true;
    }

    public function notifRefusedMedia(User $author, Media $media): bool 
    {
        $notification = new Notification();

        $notification->setText("Votre média \"" . $media->getTitle() . "\" a été refusé par la modération");

        $notification->setDateCreation(new \DateTime());
        $notification->setUser($author);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        return true;
    }

    public function notifValidatedTopic(User $author, Topic $topic): bool 
    {
        $notification = new Notification();

        $notification->setText("Votre topic \"" . $topic->getTitle() . "\" a été approuvé par la modération");

        $notification->setDateCreation(new \DateTime());
        $notification->setUser($author);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        return true;
    }

    public function notifRefusedTopic(User $author, Topic $topic): bool 
    {
        $notification = new Notification();

        $notification->setText("Votre topic \"" . $topic->getTitle() . "\" a été refusé par la modération");

        $notification->setDateCreation(new \DateTime());
        $notification->setUser($author);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        return true;
    }
}
