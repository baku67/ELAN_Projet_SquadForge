<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\User;
use App\Entity\Topic;
use App\Entity\Media;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {

            if ($this->getUser()->isVerified()) {
                $_SESSION["error"] = "Your account is not verified.";
                return $this->render('security/frerfzfeqzeq.html.twig');
                // throw new CustomUserMessageAuthenticationException('Your account is not verified.');
            }
            else {
                // return $this->redirectToRoute('app_home');
                return $this->render('security/home.html.twig');
            }
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }



    // Déconnexion déconnection
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }



    // Homepage 
    #[Route(path: '/', name: 'app_landingPage')]
    #[Route(path: '/home', name: 'app_home')]
    public function homepage(EntityManagerInterface $entityManager)
    {
        $notifRepo = $entityManager->getRepository(Notification::class);
        $mediaRepo = $entityManager->getRepository(Media::class);
        $topicRepo = $entityManager->getRepository(Topic::class);

        // Onglet notifs Bulle nbr "non-vues" (int si connécté, null sinon)
        $userNotifCount = $this->getUser() ? count($notifRepo->findByUserNotSeen($this->getUser())) : null;
        // Si userModo: Bulles nbr éléments en attente de validation (int si modo, null sinon)
        if(in_array('ROLE_MODO', $this->getUser()->getRoles())) {
            // On compte les Topic et Médias status "waiting"
            $mediasWaitings = count($mediaRepo->findBy(["validated" => "waiting"]));
            $topicsWaitings = count($topicRepo->findBy(["validated" => "waiting"]));
            $modoNotifCount = $mediasWaitings + $topicsWaitings;
        }
        else {
            $modoNotifCount = null;
        }

        // Si connecté: raccourcis Games favoris
        if($this->getUser()) {
            $userFav = $this->getUser()->getFavoris();
        }
        else {
            $userFav = null;
        }

        // Homepage: 5 derniers Topics
        $topicManager = $entityManager->getRepository(Topic::class);
        $lastTopics = $topicManager->findLastTopics();

        // Homepage: 5 derniers Médias
        $MediaManager = $entityManager->getRepository(Media::class);
        $lastMedias = $MediaManager->findLastMedias();
                
        return $this->render('security/home.html.twig', [
            'modoNotifCount' => $modoNotifCount,
            'userNotifCount' => $userNotifCount,
            'userFav' => $userFav,
            'lastTopics' => $lastTopics,
            'lastMedias' => $lastMedias,
        ]);
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
