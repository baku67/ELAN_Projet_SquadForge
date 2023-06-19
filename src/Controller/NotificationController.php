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


    // Page de listing notifications User connecté 
    #[Route('/showNotifsList', name: 'app_showNotifsList')]
    public function showNotifsList(Request $request): Response
    {
        $user = $this->getUser();

        //***** */ Si userModo: Bulles nbr éléments en attente de validation (int si modo, null sinon) 
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

        $notifRepo = $this->entityManager->getRepository(Notification::class);
        $notifs = $notifRepo->findBy(["user" => $this->getUser()], ["date_creation" => "DESC"]);

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



    
    // Clean des notifs user (TODO ajax + gérer le bouton clean si 0)
    #[Route('/deleteNotif/{notifId}', name: 'app_deleteNotif')]
    public function deleteNotif(Request $request, int $notifId): Response
    {
        // Vérif si "possède" bien la notif
        $user = $this->getUser();
        $notifs = $user->getNotifications();

        $notifRepo = $this->entityManager->getRepository(Notification::class);
        $targetedNotif = $notifRepo->find($notifId);

        $notifRepo->remove($targetedNotif, true);

        return $this->redirectToRoute('app_showNotifsList');
    }




    // Clean des notifs user (TODO ajax + gérer le bouton clean si 0)
    #[Route('/cleanNotifsUser', name: 'app_cleanNotifsUser')]
    public function cleanNotifsUser(Request $request): Response
    {
        $user = $this->getUser();

        $notifRepo = $this->entityManager->getRepository(Notification::class);
        $notifs = $user->getNotifications();

        // Delete all notifs user
        foreach ($notifs as $notif) {
            $notifRepo->remove($notif, true);
        }

        return $this->redirectToRoute('app_showNotifsList');
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
        $notification->setClicked(false);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        // Lien notif post-add pour récup l'id notif et l'injecter dans la route pour state "clicked":
        $link = $this->urlGenerator->generate('app_groupDetails', ['groupId' => $group->getId(), 'notifId' => $notification->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        $notification->setLink($link);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        return true;
    }


    public function notifNewMember(User $newMember, Group $group) 
    {
        $members = $group->getMembers();

        // Sauf le leader (pas besoin de notif alors que c'est lui qui a validé) et membre accepté (autre notif)
        foreach ($members as $member) {

            if ($member != $group->getLeader() || $member != $newMember) {
                $notification = new Notification();

                $notification->setText('"' . $newMember->getPseudo() . '" a intégré la team "' . $group->getTitle());
    
                $notification->setDateCreation(new \DateTime());
                $notification->setUser($member);
                $notification->setClicked(false);
        
                $this->entityManager->persist($notification);  
                $this->entityManager->flush();
                
                // Lien notif post-add pour récup l'id notif et l'injecter dans la route pour state "clicked":
                $link = $this->urlGenerator->generate('app_groupDetails', ['groupId' => $group->getId(), 'notifId' => $notification->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
                $notification->setLink($link);
                $this->entityManager->persist($notification);  
                $this->entityManager->flush();
            }
        }
        return true;
    }


    public function notifNewLeader(User $newLeader, Group $group): bool
    {
        $notification = new Notification();

        // Message de la notif au nouveau leader
        $notification->setText("Vous êtes désormais leader de la team \"" . $group->getTitle() . "\"");

        $notification->setDateCreation(new \DateTime());
        $notification->setUser($newLeader);
        $notification->setClicked(false);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        // Lien notif post-add pour récup l'id notif et l'injecter dans la route pour state "clicked":
        $link = $this->urlGenerator->generate('app_groupDetails', ['groupId' => $group->getId(), 'notifId' => $notification->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        $notification->setLink($link);
            
        // Notifs aux autres membres (nouveau leader exclus)
        $members = $group->getMembers();

        foreach ($members as $member) {
            if($member != $newLeader) {
                $notification2 = new Notification();

                // Message de la notif
                $notification2->setText("\"" . $newLeader->getPseudo() . "\" est désormai leader de la team \"" . $group->getTitle() . "\"");
                
                $notification2->setDateCreation(new \DateTime());
                $notification2->setUser($member);
                $notification2->setClicked(false);
    
                $this->entityManager->persist($notification2);
                $this->entityManager->flush();

                // Lien notif post-add pour récup l'id notif et l'injecter dans la route pour state "clicked":
                $link = $this->urlGenerator->generate('app_groupDetails', ['groupId' => $group->getId(), 'notifId' => $notification->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
                $notification2->setLink($link);
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
            
            $notification->setDateCreation(new \DateTime());
            $notification->setUser($member);
            $notification->setClicked(false);

            $this->entityManager->persist($notification);
            $this->entityManager->flush();

            // Lien notif post-add pour récup l'id notif et l'injecter dans la route pour state "clicked":
            $link = $this->urlGenerator->generate('app_groupDetails', ['groupId' => $group->getId(), 'notifId' => $notification->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
            $notification->setLink($link);
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
        $notification->setClicked(false);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        // Lien notif post-add pour récup l'id notif et l'injecter dans la route pour state "clicked":
        $link = $this->urlGenerator->generate('app_groupDetails', ['groupId' => $group->getId(), 'notifId' => $notification->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        $notification->setLink($link);
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
        $notification->setClicked(false);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        // Lien notif post-add pour récup l'id notif et l'injecter dans la route pour state "clicked":
        $link = $this->urlGenerator->generate('app_candidatureDetails', ['candidatureId' => $candidature->getId(), 'notifId' => $notification->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        $notification->setLink($link);
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
        $notification->setClicked(false);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        // Lien notif post-add pour récup l'id notif et l'injecter dans la route pour state "clicked":
        $link = $this->urlGenerator->generate('app_mediaDetail', ['id' => $media->getId(), 'notifId' => $notification->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        $notification->setLink($link); 
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
        $notification->setClicked(false);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();
        
        // Lien notif (format localhost pour test mais marche en prod normalement):
        $link = $this->urlGenerator->generate('app_user', ['notifId' => $notification->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        $notification->setLink($link);    
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
        $notification->setClicked(false);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        // Lien notif (format localhost pour test mais marche en prod normalement):
        $link = $this->urlGenerator->generate('app_topicDetail', ['id' => $topic->getId(), 'notifId' => $notification->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        $notification->setLink($link); 
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
        $notification->setClicked(false);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        // Lien notif (format localhost pour test mais marche en prod normalement):
        $link = $this->urlGenerator->generate('app_user', ['notifId' => $notification->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        $notification->setLink($link); 
        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        return true;
    }




    // *************** Notifs de likes simples (non groupés, TODO) ****************
    
}
