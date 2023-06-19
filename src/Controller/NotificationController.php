<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\Candidature;
use App\Entity\Media;
use App\Entity\Group;
use App\Entity\Topic;
use App\Entity\User;
use App\Repository\NotificationRepository;

use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{

    private $urlGenerator;
    private $entityManager;
    private $notifRepo;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, NotificationRepository $notifRepo)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->notifRepo = $notifRepo;
    }


    // Page de listing notifications User connecté (OrderBy HS: Le notifRepo marche pas ni avec entityManager )
    #[Route('/showNotifsList', name: 'app_showNotifsList')]
    public function showNotifsList(Request $request): Response
    {
        $user = $this->getUser();

        //***** */ Si userModo: Bulles nbr éléments en attente de validation (int si modo, null sinon) 
        // WTF CES REPO-LA ILS MARCHENT !? (Marche pas avec Notification)
        $mediaRepo = $this->entityManager->getRepository(Media::class);
        $topicRepo = $this->entityManager->getRepository(Topic::class);
        if($this->getUser() && in_array('ROLE_MODO', $this->getUser()->getRoles())) {
            // On compte les Topic et Médias status "waiting"
            $mediasWaitings = count($mediaRepo->findBy(["validated" => "waiting"]));
            $topicsWaitings = count($topicRepo->findBy(["validated" => "waiting"]));
            $modoNotifCount = $mediasWaitings + $topicsWaitings;
        }
        else {
            $modoNotifCount = null;
        }
        //*********************************************************************************** */

        // (OrderBy HS: Le notifRepo marche pas ni avec entityManager )
        // $notifRepo = $this->entityManager->getRepository(Notification::class);
        // $notifs = $this->notifRepo->findAll();
        $notifs = $user->getNotifications();

        // Toutes les notifs passent en "seen" (pas de /notifDetails)
        foreach ($notifs as $notif) {
            $notif->setSeen(true);
            $this->entityManager->persist($notif);
        }
        $this->entityManager->flush();

        return $this->render('user/notifsList.html.twig', [
            'modoNotifCount' => $modoNotifCount,
            'user' => $user,
            'notifs' => $notifs,
        ]);
    }


    // Clean des notifs user (HS notifRepo !!!! j'ai tout tenté)
    #[Route('/cleanNotifsUser', name: 'app_cleanNotifsUser')]
    public function cleanNotifsUser(Request $request): Response
    {
        $user = $this->getUser();

        $notifRepo = $this->entityManager->getRepository(Notification::class);
        $notifs = $notifRepo->findAll();
        // $notifs = $user->getNotifications();

        // Delete all notifs user
        // foreach ($notifs as $notif) {
        //     $notifRepo->remove($notif, true);
        // }

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

        // Lien notif (format localhost pour test mais marche en prod normalement):
        $link = $this->urlGenerator->generate('app_groupDetails', ['groupId' => $group->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        $notification->setLink($link);
            
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

        // Lien notif (format localhost pour test mais marche en prod normalement):
        $link = $this->urlGenerator->generate('app_groupDetails', ['groupId' => $group->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        $notification->setLink($link);
        
        $notification->setDateCreation(new \DateTime());
        $notification->setUser($newLeader);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        // Notifs aux autres membres (nouveau leader exclus)
        $members = $group->getMembers();

        foreach ($members as $member) {
            if($member != $newLeader) {
                $notification2 = new Notification();

                // Message de la notif
                $notification2->setText("\"" . $newLeader->getPseudo() . "\" est désormai leader de la team \"" . $group->getTitle() . "\"");
                
                // Lien notif (format localhost pour test mais marche en prod normalement):
                $link = $this->urlGenerator->generate('app_groupDetails', ['groupId' => $group->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
                $notification2->setLink($link);

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
        $groupRepo = $this->entityManager->getRepository(Group::class);
        $members = $group->getMembers();

        foreach ($members as $member) {
            $notification = new Notification();

            // Message de la notif
            $notification->setText("\"" . $leavingUser->getPseudo() . "\" a quitté la team \"" . $group->getTitle() . "\"");
            
            // Lien notif (format localhost pour test mais marche en prod normalement):
            $link = $this->urlGenerator->generate('app_groupDetails', ['groupId' => $group->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
            $notification->setLink($link);

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

        // Lien notif (format localhost pour test mais marche en prod normalement):
        $link = $this->urlGenerator->generate('app_groupDetails', ['groupId' => $group->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        $notification->setLink($link);
            

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

        // Lien notif (format localhost pour test mais marche en prod normalement):
        $link = $this->urlGenerator->generate('app_candidatureDetails', ['candidatureId' => $candidature->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        $notification->setLink($link);

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

        // Lien notif (format localhost pour test mais marche en prod normalement):
        $link = $this->urlGenerator->generate('app_mediaDetail', ['id' => $media->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        $notification->setLink($link); 

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

        // Lien notif (format localhost pour test mais marche en prod normalement):
        $link = $this->urlGenerator->generate('app_user', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $notification->setLink($link);    

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

        // Lien notif (format localhost pour test mais marche en prod normalement):
        $link = $this->urlGenerator->generate('app_topicDetail', ['id' => $topic->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        $notification->setLink($link); 
    
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

        // Lien notif (format localhost pour test mais marche en prod normalement):
        $link = $this->urlGenerator->generate('app_user', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $notification->setLink($link); 

        $notification->setDateCreation(new \DateTime());
        $notification->setUser($author);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        return true;
    }
}
