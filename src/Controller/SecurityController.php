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
use App\Entity\Report;

use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\OAuthTwitch;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;

class SecurityController extends AbstractController
{

    private $notifController;

    public function __construct(NotificationController $notifController, private RequestStack $requestStack) {

        $this->notifController = $notifController;
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

        $session = $this->requestStack->getSession();
        $session->start();
        
        $oauth = new OAuthTwitch('9xmxl9h3npck0tvgcdejwzeczhbl0w', 'l0qj5m6wmay7k28z20a48s7f74xs3x', 'http://localhost:8000/oauthCallback', 'user:read:email+user:read:follows');

        $link = $oauth->get_link_connect();

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'linkTwitchOAuth' => $link,
            'error' => $error]);
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
            $gameRepo = $entityManager->getRepository(Game::class);
            $reportRepo = $entityManager->getRepository(Report::class);

            $allGames = $gameRepo->findAll();

            // Onglet notifs Bulle nbr "non-vues" (int si connécté, null sinon)
            $userNotifCount = $this->getUser() ? count($notifRepo->findByUserNotSeen($this->getUser())) : null;
            // Si userModo: Bulles nbr éléments en attente de validation (int si modo, null sinon)
            if($this->getUser() && in_array('ROLE_MODO', $this->getUser()->getRoles())) {
                // On compte les Topic et Médias status "waiting"
                $mediasWaitings = count($mediaRepo->findBy(["validated" => "waiting"]));
                $topicsWaitings = count($topicRepo->findBy(["validated" => "waiting"]));
                $modoNotifValidationCount = $mediasWaitings + $topicsWaitings;
                // Nombre de cards report (groupée, != nbr reports)
                $modoNotifReportCount = count($reportRepo->getAllReportsGroupedByOjectIdAndType());
            }
            else {
                $modoNotifValidationCount = null;
                $modoNotifReportCount = null;
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
                'modoNotifValidationCount' => $modoNotifValidationCount,
                'modoNotifReportCount' => $modoNotifReportCount,
                'userNotifCount' => $userNotifCount,
                'userFav' => $userFav,
                'allGames' => $allGames,
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
                
                $oauth = new OAuthTwitch('9xmxl9h3npck0tvgcdejwzeczhbl0w', 'l0qj5m6wmay7k28z20a48s7f74xs3x', 'http://localhost:8000/oauthCallback', 'user:read:email+user:read:follows');

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
    public function oauthCallback(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = $this->requestStack->getSession();

        $oauth = new OAuthTwitch('9xmxl9h3npck0tvgcdejwzeczhbl0w', 'l0qj5m6wmay7k28z20a48s7f74xs3x', 'http://localhost:8000/oauthCallback', 'user:read:email+user:read:follows');

        if(!empty($request->query->get('code'))) {
            $code = htmlspecialchars(($request->query->get('code')));
            $token = $oauth->get_token($code, $entityManager);

            $_SESSION['token'] = $token;
            $session->set('token', $token);


            $twitchUser = $oauth->get_user();
            $user = new User();
            $user->setPseudo("test");
            $user->setEmail("test");
            $user->setPassword("test");
            $user->setNbrCensures(0);
            // $user->setTwitchId($twitchUser['data'][0]['id']);
            $entityManager->persist($user);
            $entityManager->flush();
        }
        else {
            // HS car le code passe par ici (pourtant code bien présent dans l'url callback)
            $session->set('token', 'testEmptyGetCode22');
        }

        return $this->render('security/testOAuth.html.twig', [
            'testToken' => $session->get('token'),
            'testGetCodeParam' => $request->query->get('code'),
            'twitchUser' => $twitchUser,
        ]);
    }



    // /registerForm: Ajax check Pseudo available critère front
    #[Route(path: '/checkPseudoAvailable/{inputPseudo}', name: 'app_checkPseudoAvailable')]
    public function checkPseudoAvailable(EntityManagerInterface $entityManager, Request $request, string $inputPseudo): Response
    {

        $userRepo = $entityManager->getRepository(User::class);
        $checkPseudo = $userRepo->findBy(['pseudo' => $inputPseudo]);

        if($checkPseudo) {
            $pseudoAvailable = false;
        }
        else {
            $pseudoAvailable = true;
        }

        
        return new JsonResponse(
            [
                'success' => true,
                'pseudoAvailable' => $pseudoAvailable
            ]
        );


    }



    // Page "Politique de confidentialité"
    #[Route(path: '/privacy', name: 'app_privacy')]
    public function privacy(): Response
    {
        return $this->render('security/privacy.html.twig', []);
    }



    
    // TODO: Changement de mots de passe 
    #[Route(path: '/changePassword', name: 'app_changePassword')]
    public function changePassword(EntityManagerInterface $entityManager): Response
    {



        $this->addFlash('error', 'Fonctionalité à venir');
        return $this->redirectToRoute('app_user');

    }


    // Suppression de compte par l'User
    #[Route(path: '/deleteSelfAccount', name: 'app_deleteSelfAccount')]
    public function deleteSelfAccount(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): Response
    {
    
        // Vérif User logged In
        if($this->getUser()) {

            $userToDelete = $this->getUser();

            $tokenStorage->setToken(null);

            // Gestion memberships
            // Si après le leave, il reste des membre et que l'User était leader, passe le lead, si plus aucun membre: suppr le groupe
            $groups = $userToDelete->getGroupes();
            foreach ($groups as $group) {

                // Suppression des inscriptions aux sessions de groupes:
                $sessions = $userToDelete->getGroupSessionDispos();
                foreach ($sessions as $session) {
                    $userToDelete->removeGroupSessionDispo($session);
                }

                // Retire le membre du groupe:
                $userToDelete->removeGroupe($group);
                $entityManager->persist($userToDelete);

                $this->notifController->notifMemberLeave($group, $this->getUser());

                $members = $group->getMembers();
                if ($members->count() > 0) {
                    if ( $group->getLeader() == $this->getUser() ) {

                        $membersArray = $members->toArray();
                        $randomIndex = array_rand($membersArray);
                        $randomMember = $membersArray[$randomIndex];
                        $group->setLeader($randomMember);
                        // Envoi notif au nouveau leader
                        $this->notifController->notifNewLeader($randomMember, $group);

                        $entityManager->persist($group);

                        $entityManager->flush(); 
                    }
                // Si apprès le leave, aucun membre: suppr le groupe
                } 
                else {
                    $groupRepo->remove($group);
                }
            }


            // Suppression des report

            // Gestion publications
            // anonymisation des publications de l'user => NULL (contenu gardé):
            $topics = $userToDelete->getTopics();
            foreach ($topics as $topic) {
                $userToDelete->removeTopic($topic);
            }

            $medias = $userToDelete->getMedia();
            foreach ($medias as $media) {
                $userToDelete->removeMedia($media);
            }


            $topicPosts = $userToDelete->getTopicPosts();
            foreach ($topicPosts as $topicPost) {
                $userToDelete->removeTopicPost($topicPost);
            }

            $mediaPosts = $userToDelete->getMediaPosts();
            foreach ($mediaPosts as $mediaPost) {
                $userToDelete->removeMediaPost($mediaPost);
            }

            
            // Suppression User:
            $entityManager->remove($userToDelete);
            $entityManager->flush();

            $this->addFlash('success', 'Votre compte a bien été supprimé');
            return $this->redirectToRoute('app_home');

        }
        else {
            $this->addFlash('error', 'Vous devez être connecté pour supprimer votre compte');
            return $this->redirectToRoute('app_home');
        }

    }

}
