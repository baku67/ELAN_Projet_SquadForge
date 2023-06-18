<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\User;
use App\Entity\Game;
use App\Entity\Topic;
use App\Entity\Media;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{

    
    #[Route('/user', name: 'app_user')]
    public function profil(EntityManagerInterface $entityManager): Response
    {
        $notifRepo = $entityManager->getRepository(Notification::class);
        // Onglet notifs Bulle nbr "non-vues" (int si connécté, null sinon)
        $userNotifCount = $this->getUser() ? count($notifRepo->findByUserNotSeen($this->getUser())) : null;

        if ($this->getUser()) {

            $userRepo = $entityManager->getRepository(User::class);

            $user = $userRepo->find($this->getUser()->getId());
            $userRole = $this->getUser()->getRoles();
            $userFav = $this->getUser()->getFavoris();

            // Derniers Topics du user (+ count total DQL)
            $topicRepo = $entityManager->getRepository(Topic::class);
            $userTopics = $topicRepo->findUserLastTopics($user);
            $userTopicsCount = $topicRepo->countUserTopics($user);

            // Derniers médias du user (+ count total DQL)
            $mediaRepo = $entityManager->getRepository(Media::class);
            $userMedias = $mediaRepo->findUserLastMedias($user);
            $userMediasCount = $mediaRepo->countUserMedias($user);

            return $this->render('user/profil.html.twig', [
                'userNotifCount' => $userNotifCount,
                'user' => $user,
                'userRole' => $userRole,
                'userFav' => $userFav,
                'userTopics' => $userTopics,
                'userTopicsCount' => $userTopicsCount,
                'userMedias' => $userMedias,
                'userMediasCount' => $userMediasCount,
            ]);
        }
        else {
            return $this->render('security/login.html.twig', [
            ]);
        }
    }



    // Ajax Asynch toggleAutoplayGifs (A fixer! juste inversion bool BDD pour l'instant)
    #[Route('/toggleAutoplayGifs', name: 'app_toggleAutoplayGifs')]
    public function toggleAutoplayGifs(EntityManagerInterface $entityManager, Request $request): Response
    {

        $user = $this->getUser();

        // On inverse le state BDD (à changer par value checkbox)
        if(!$user->isAutoPlayGifs()) {
            $user->setAutoPlayGifs(true);
        }
        else {
            $user->setAutoPlayGifs(false);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['success' => true]); 
    }



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

}
