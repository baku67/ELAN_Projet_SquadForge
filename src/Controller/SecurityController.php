<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\User;
use App\Entity\Topic;
use App\Entity\Media;
use App\Entity\Group;
use App\Entity\GroupSession;

use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\OAuthTwitch;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    public function __construct(private RequestStack $requestStack){
    }


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
                return $this->redirectToRoute('app_home');
                // return $this->render('security/home.html.twig');
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



    // Homepage (différentes selon User connected ou non)
    #[Route(path: '/', name: 'app_landingPage')]
    #[Route(path: '/home', name: 'app_home')]
    public function homepage(EntityManagerInterface $entityManager, Request $request)
    {

        if($this->getUser()) {

            $notifRepo = $entityManager->getRepository(Notification::class);
            $mediaRepo = $entityManager->getRepository(Media::class);
            $topicRepo = $entityManager->getRepository(Topic::class);

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

            // Si connecté: raccourcis Games favoris, et listTeams
            if($this->getUser()) {
                $userFav = $this->getUser()->getFavoris();
                $userTeams = $this->getUser()->getGroupes();
            }
            else {
                $userFav = null;
                $userTeams = null;
            }

            // Homepage: derniers Topics/Médias (Que à propos des jeux fav si connected)
            $topicManager = $entityManager->getRepository(Topic::class);
            $MediaManager = $entityManager->getRepository(Media::class);

            if($this->getUser()) {
                $lastTopics = $topicManager->findLastTopicsFav($userFav);
                $lastMedias = $MediaManager->findLastMediasFav($userFav);
            }
            else {
                $lastTopics = $topicManager->findLastTopics();
                $lastMedias = $MediaManager->findLastMedias();
            }
            
                    
            return $this->render('security/home.html.twig', [
                'modoNotifCount' => $modoNotifCount,
                'userNotifCount' => $userNotifCount,
                'userFav' => $userFav,
                'userTeams' => $userTeams,
                'lastTopics' => $lastTopics,
                'lastMedias' => $lastMedias,
            ]);
            throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
        
        }
        else {

            $userRepo = $entityManager->getRepository(User::class);
            $groupRepo = $entityManager->getRepository(Group::class);
            $topicRepo = $entityManager->getRepository(Topic::class);
            $mediaRepo = $entityManager->getRepository(Media::class);
            $sessionRepo = $entityManager->getRepository(GroupSession::class);
            $gameRepo = $entityManager->getRepository(Game::class);

            // Lien twitch oauth:
                // session_start();
                $session = $this->requestStack->getSession();
                $session->start();
                
                $oauth = new OAuthTwitch('9xmxl9h3npck0tvgcdejwzeczhbl0w', 'l0qj5m6wmay7k28z20a48s7f74xs3x', 'http://localhost:8000/oauthCallback', 'user:read:email');

                $link = $oauth->get_link_connect();

                
            // fin lien twitch oauth

            $usersCount = count($userRepo->findAll());
            $teamsCount = count($groupRepo->findAll());

            $topicsCount = count($topicRepo->findAll());
            $mediasCount = count($mediaRepo->findAll());

            $games = $gameRepo->findAll();
            $gamesCount = count($gameRepo->findAll());

            $sessionsCount = count($sessionRepo->findAll());

            return $this->render('security/landingPage.html.twig', [
                'usersCount' => $usersCount,
                'teamsCount' => $teamsCount,
                'topicsCount' => $topicsCount,
                'mediasCount' => $mediasCount,
                'gamesCount' => $gamesCount,
                'sessionsCount' => $sessionsCount,
                'games' => $games,
                'linkTwitchOAuth' => $link,
                'testToken' => $session->get('token'),
            ]);

        }
    
    }

    #[Route(path: '/getLoginForm', name: 'app_getLoginForm')]
    public function getLoginForm(): Response
    {
        return $this->render('security/login_form.html.twig');
    }


    #[Route(path: '/oauthCallback', name: 'app_oauthCallback')]
    public function oauthCallback(Request $request): Response
    {
        $session = $this->requestStack->getSession();

        $oauth = new OAuthTwitch('9xmxl9h3npck0tvgcdejwzeczhbl0w', 'l0qj5m6wmay7k28z20a48s7f74xs3x', 'http://localhost:8000/oauthCallback', 'user:read:email');

        if(!empty($request->query->get('code'))) {
            $code = htmlspecialchars(($request->query->get('code')));
            $token = $oauth->get_token($code);

            // $_SESSION['token'] = $token;
            $session->set('token', $token);
        }
        else {
            // HS car le code passe par ici (pourtant code bien présent dans l'url callback)
            $session->set('token', 'testEmptyGetCode22');
        }

        return $this->render('security/testOAuth.html.twig', [
            'testToken' => $session->get('token'),
            'testGetCodeParam' => $request->query->get('code'),
        ]);
    }
}
