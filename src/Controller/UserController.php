<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\User;
use App\Entity\Game;
use App\Entity\Topic;
use App\Entity\Media;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{

    
    #[Route('/user/{notifId}', name: 'app_user', defaults: ['notifId' => null])]
    public function profil(EntityManagerInterface $entityManager, int $notifId = null): Response
    {
        $mediaRepo = $entityManager->getRepository(Media::class);
        $topicRepo = $entityManager->getRepository(Topic::class);
        $notifRepo = $entityManager->getRepository(Notification::class);

        // Si page vient de notifId, passe la notif en "clicked"
        if (!is_null($notifId)) {
            $notifFrom = $notifRepo->find($notifId);
            $notifFrom->setClicked(true);
            $entityManager->persist($notifFrom);
            $entityManager->flush();
        }

        // Onglet notifs Bulle nbr "non-vues" (int si connécté, null sinon)
        $userNotifCount = $this->getUser() ? count($notifRepo->findByUserNotSeen($this->getUser())) : null;
        // Si userModo: Bulles nbr éléments en attente de validation (int si modo, null sinon)
        if($this->getUser() && in_array('ROLE_MODO', $this->getUser()->getRoles())) {
            // On compte les Topic et Médias status "waiting"
            $mediasWaitings = count($mediaRepo->findBy(["validated" => "waiting"]));
            $topicsWaitings = count($topicRepo->findBy(["validated" => "waiting"]));
            $modoNotifCount = $mediasWaitings + $topicsWaitings;
        }
        else {
            $modoNotifCount = null;
        }

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
                'modoNotifCount' => $modoNotifCount,
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


}
