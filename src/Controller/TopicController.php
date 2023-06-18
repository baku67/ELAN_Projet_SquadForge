<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\Censure;
use App\Entity\Topic;
use App\Entity\TopicPost;
use App\Entity\PostLike;
use App\Form\TopicPostType;
use App\Entity\Game;

use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TopicController extends AbstractController
{

    // Tout les Topics du jeu et tout les Topics globaux (pour pouvoir switch le filtre sur la vue)
    #[Route('/allTopics/{gameIdFrom}', name: 'app_allTopics')]
    public function getAllTopics(EntityManagerInterface $entityManager, int $gameIdFrom): Response
    {
        $gameRepo = $entityManager->getRepository(Game::class);
        $gameFrom = $gameRepo->find($gameIdFrom);
        $topicRepo = $entityManager->getRepository(Topic::class);

        $notifRepo = $entityManager->getRepository(Notification::class);
        // Onglet notifs Bulle nbr "non-vues" (int si connécté, null sinon)
        $userNotifCount = $this->getUser() ? count($notifRepo->findByUserNotSeen($this->getUser())) : null;

        // Liste de tous les Topics du jeu (le jeu d'où vient la requete) max 50
        $gameTopicsDesc = $topicRepo->findGameLastTopics($gameFrom);
        $gameTopicsCount = $topicRepo->countGameTopics($gameFrom);

        // Trier par nbrPosts (à voir parce que ducoup les nouveaux topics sont éclipsés)
        // $sortedTopics = $gameTopicsDesc;
        // usort($sortedTopics, function ($a, $b) {
        //     return $b->getTopicPostsCount() - $a->getTopicPostsCount();
        // });

        $allTopicsDesc = $topicRepo->findAllTopics();
        $allTopicsCount = $topicRepo->countAllTopics();

        if ($gameIdFrom != "home") {
            $from = "game";
        }
        else {
            $from = "home";
        }

        return $this->render('topic/index.html.twig', [
            'userNotifCount' => $userNotifCount,
            'allTopicsDesc' => $allTopicsDesc,
            'allTopicsCount' => $allTopicsCount,
            'gameTopicsDesc' => $gameTopicsDesc,
            'gameTopicsCount' => $gameTopicsCount,
            'gameFrom' => $gameFrom,
            'from' => $from,
        ]);
    }


    
    // Tout les Topics globaux (from /homePage)
    #[Route('/allTopicsGlobal', name: 'app_allTopicsGlobal')]
    public function getAllTopicsGlobal(EntityManagerInterface $entityManager): Response
    {
        $gameRepo = $entityManager->getRepository(Game::class);
        $topicRepo = $entityManager->getRepository(Topic::class);

        $notifRepo = $entityManager->getRepository(Notification::class);
        // Onglet notifs Bulle nbr "non-vues" (int si connécté, null sinon)
        $userNotifCount = $this->getUser() ? count($notifRepo->findByUserNotSeen($this->getUser())) : null;

        // Liste de tous les Topics 
        $allTopicsDesc = $topicRepo->findBy([], ['publish_date' => 'DESC'], 50);
        $allTopicsCount = $topicRepo->countAllTopics();

        return $this->render('topic/allTopicsGlobal.html.twig', [
            'userNotifCount' => $userNotifCount,
            'allTopicsDesc' => $allTopicsDesc,
            'allTopicsCount' => $allTopicsCount,
        ]);
    }



    // Tout les Topics de l'user connecté (pas d'UserId passé donc) (from profil)
    #[Route('/allTopicsUser', name: 'app_allTopicsUser')]
    public function getAllTopicsUser(EntityManagerInterface $entityManager): Response
    {
        $topicRepo = $entityManager->getRepository(Topic::class);
        $notifRepo = $entityManager->getRepository(Notification::class);
        // Onglet notifs Bulle nbr "non-vues" (int si connécté, null sinon)
        $userNotifCount = $this->getUser() ? count($notifRepo->findByUserNotSeen($this->getUser())) : null;

        // Sur le profil: On affiche les status "validated" "en attente" des Topics et Médias
        $userTopicsDesc = $topicRepo->findBy(['user' => $this->getUser()], ['publish_date' => 'DESC']);
        // $userTopicsDesc = $topicRepo->findAllTopicsByUserValidated($user);
        $userTopicsCount = count($userTopicsDesc);

        return $this->render('user/allTopicsUser.html.twig', [
            'userNotifCount' => $userNotifCount,
            'userTopics' => $userTopicsDesc,
            'userTopicsCount' => $userTopicsCount,
        ]);

    }


    // Topic Details (id: idTopic)
    #[Route('/topicDetail/{id}', name: 'app_topicDetail')]
    public function getTopicDetail(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        $censureRepo = $entityManager->getRepository(Censure::class);
        $topicRepo = $entityManager->getRepository(Topic::class);
        $topicPostRepo = $entityManager->getRepository(TopicPost::class);
        $notifRepo = $entityManager->getRepository(Notification::class);
        // Onglet notifs Bulle nbr "non-vues" (int si connécté, null sinon)
        $userNotifCount = $this->getUser() ? count($notifRepo->findByUserNotSeen($this->getUser())) : null;

        $topic = $topicRepo->find($id);

        if ($topic->getValidated() == "validated") {

            $censures = $censureRepo->findAll();

            $topicGame = $topic->getGame();

            // A remplacer par customQuery: triés par nbr d'upvote et sinon par publishDate (récent en haut) [différent d'un chat]
            // + On cherche uniquement les posts qui ne répondent pas à un post (parent = null (nullable))
            // (les réponses au post s'afficheront avec ajax au click sur le post)
            $topicPosts = $topicPostRepo->findBy(['topic' => $topic], ['publish_date' => 'DESC']);

            // Form de publication de post sur un topic
            $topicPost = new TopicPost();
            $form = $this->createForm(TopicPostType::class, $topicPost);
            $form -> handleRequest($request);

            // Vérifs/Filtres
            if($form->isSubmitted()) {

                // Vérif connecté pour poster un TopicPost
                if($this->getUser()) {

                    // Vérification si le topic est ouvert
                    if ($topic->getStatus() == "open") {
                        
                        if($form->isValid()) {

                            // Hydrataion du "TopicPost" a partir des données du form
                            $topicPost = $form->getData();
        
                            // Init de la publish_date du comment
                            $topicPost->setPublishDate(new \DateTime());
                            $topicPost->setUser($this->getUser());
                            $topicPost->setTopic($topic);
                            
                            // Désactivation vérification nbr de mots etc...
                            // // Récupération du titre
                            // $textInputValue = $form->get('text')->getData();
                            // // Liste des mots du commentaires
                            // $words = str_word_count($textInputValue, 1);
                            // // Décompte du nombre de mots dans la liste
                            // $wordCount = count($words);
                            // // Vérification du compte de mots
                            // if ($wordCount >= 5) {
        
                            // Modifs Base de données
                            $entityManager->persist($topicPost);
                            $entityManager->flush();
    
                            $this->addFlash('success', 'Le post a bien été publié');
                            return $this->redirectToRoute('app_topicDetail', ['id' => $topic->getId()]);
                            // } else {
                                
                            //     $this->addFlash('error', 'Le titre doit faire au minimum 5 mots !');
                            //     return $this->redirectToRoute('app_game', ['id' => $game->getId()]);
                            // }
                        } 
                        else {
                            $this->addFlash('error', 'Pas de vulgarités pour un titre');
                            return $this->redirectToRoute('app_topicDetail', ['id' => $topic->getId()]);
                        }   
                    }
                    else {
                        $this->addFlash('error', 'Le topic a été fermé, vous ne pouvez plus le commenter.');
                        return $this->redirectToRoute('app_topicDetail', ['id' => $topic->getId()]);
                    }
                }
                else {
                    $this->addFlash('error', 'Vous devez être connecté pour publier un post');
                    return $this->redirectToRoute('app_login');
                }
            }

            return $this->render('topic/topicDetail.html.twig', [
                'userNotifCount' => $userNotifCount,
                'formAddTopicPost' => $form->createView(),
                'topic' => $topic,
                'game' => $topicGame,
                'topicPosts' => $topicPosts,
                'censures' => $censures,
            ]);

        }
        else {

            $this->addFlash('error', 'Le topic est en attente ou refusé par la modération');
            return $this->redirectToRoute('app_user');
        }

    }




    // PLUS BESOIN ? (Async)

    // // Like de topicPost par user (id: idTopicPost)
    // #[Route('/likeTopicPost/{id}', name: 'app_likeTopicPost')]
    // public function likeTopicPost(EntityManagerInterface $entityManager, int $id, Request $request): Response
    // {

    //     if ($this->getUser()) {

    //         $topicPostRepo = $entityManager->getRepository(TopicPost::class);
    //         $topicPost = $topicPostRepo->find($id);
    //         $topic = $topicPost->getTopic();

    //         $topicPost->addPostLike($this->getUser());
    //         $entityManager->persist($this->getUser());
    //         $entityManager->flush();


    //         $this->addFlash('success', 'Le post a bien été liké');
    //         return $this->redirectToRoute('app_topicDetail', ['id' => $topic->getId()]);    
    //     }
    //     else {
    //         $this->addFlash('error', 'Vous devez être connecté pour liker un post');
    //         return $this->redirectToRoute('app_login');
    //     }
    
    // }


    // // Unlike de topicPost par user (id: idTopicPost)
    // #[Route('/unlikeTopicPost/{id}', name: 'app_unlikeTopicPost')]
    // public function unlikeTopicPost(EntityManagerInterface $entityManager, int $id, Request $request): Response
    // {

    //     if ($this->getUser()) {

    //         $topicPostRepo = $entityManager->getRepository(TopicPost::class);
    //         $topicPost = $topicPostRepo->find($id);
    //         $topic = $topicPost->getTopic();

    //         $topicPost->removePostLike($this->getUser());
    //         $entityManager->flush();

    //         $this->addFlash('success', 'Le post a bien été unliké');
    //         return $this->redirectToRoute('app_topicDetail', ['id' => $topic->getId()]);    
    //     }
    //     else {
    //         $this->addFlash('error', 'Vous devez être connecté pour unliker un post');
    //         return $this->redirectToRoute('app_login');
    //     }
    
    // }




    // Upvote/unUpvote de topicPost par user (id: idTopicPost) Async + calc Score
    #[Route('/upvoteTopicPost/{id}', name: 'app_upvoteTopicPost')]
    public function upvoteTopicPost(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {

        if ($this->getUser()) {

            $postLikeRepo = $entityManager->getRepository(PostLike::class);
            $topicPostRepo = $entityManager->getRepository(TopicPost::class);

            $topicPost = $topicPostRepo->find($id);
            $topic = $topicPost->getTopic();

            // Upvote possible que si pas auteur
            if ($this->getUser() != $topicPost->getUser()) {

                // Si l'utilisateur n'a pas déjà upvoté 
                if(count($postLikeRepo->findBy(['user'=>$this->getUser(), 'topicPost' =>$topicPost])) == 0) {

                    $topicPostLike = new PostLike;

                    $topicPostLike->setUser($this->getUser());
                    $topicPostLike->setTopicPost($topicPost);
                    $topicPostLike->setState("upvote");
        
                    $entityManager->persist($topicPostLike);
                    $entityManager->flush();

                    // recalcul DownVote/Upvote
                    $newScore = $postLikeRepo->calcTopicPostScore($topicPost);
        
                    // JS FLASH: Votre upvote a été pris en compte
                    return new JsonResponse(['success' => true, 'newState' => 'upvoted', 'gameColor' => $topic->getGame()->getColor(), 'newScore' => $newScore]);   

                }
                // Si l'utilisateur a déjà upvoter le post: enleve l'upvote, s'il l'a déjà downvoté, upvote
                else {

                    if($postLikeRepo->findOneBy(['user'=>$this->getUser(), 'topicPost' =>$topicPost])->getState() == "upvote" ) {

                        $topicPostLike = $postLikeRepo->findOneBy(['user'=>$this->getUser(), 'topicPost' =>$topicPost]);

                        $postLikeRepo->remove($topicPostLike, true);

                        // recalcul DownVote/Upvote
                        $newScore = $postLikeRepo->calcTopicPostScore($topicPost);

                        // AJOUTER JS FLASH: $this->addFlash('success', 'Votre upvote a été retiré');
                        return new JsonResponse(['success' => true, 'newState' => 'notUpvoted', 'newScore' => $newScore]);   

                    }
                    else if($postLikeRepo->findOneBy(['user'=>$this->getUser(), 'topicPost' =>$topicPost])->getState() == "downvote" ) {

                        $topicPostLike = $postLikeRepo->findOneBy(['user'=>$this->getUser(), 'topicPost' =>$topicPost]);

                        $topicPostLike->setState("upvote");

                        $entityManager->persist($topicPostLike);
                        $entityManager->flush();

                        // recalcul DownVote/Upvote
                        $newScore = $postLikeRepo->calcTopicPostScore($topicPost);

                        // $this->addFlash('success', 'Votre upvote a été pris en compte');
                        return new JsonResponse(['success' => true, 'newState' => 'upvoted', 'gameColor' => $topic->getGame()->getColor(), 'newScore' => $newScore]);   

                    }
                }
            }
            else {
                return new JsonResponse(['success' => false, 'newState' => 'Vous ne pouvez pas upvoter vos commentaires', 'gameColor' => $topic->getGame()->getColor(), 'newScore' => $newScore]);   
            }

        }
        else {
            return new JsonResponse(['success' => false, 'case' => 'logIn']);
        }
    
    }




    // Downvote/UnDownvote de topicPost par user (id: idTopicPost) Async + calc Score
    #[Route('/downvoteTopicPost/{id}', name: 'app_downvoteTopicPost')]
    public function downvoteTopicPost(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {

        if ($this->getUser()) {

            $postLikeRepo = $entityManager->getRepository(PostLike::class);
            $topicPostRepo = $entityManager->getRepository(TopicPost::class);

            $topicPost = $topicPostRepo->find($id);
            $topic = $topicPost->getTopic();

            // Upvote possible que si pas auteur
            if ($this->getUser() != $topicPost->getUser()) {

                // Si l'utilisateur n'a pas déjà upvoté 
                if(count($postLikeRepo->findBy(['user'=>$this->getUser(), 'topicPost' =>$topicPost])) == 0) {

                    $topicPostLike = new PostLike;

                    $topicPostLike->setUser($this->getUser());
                    $topicPostLike->setTopicPost($topicPost);
                    $topicPostLike->setState("downvote");
        
                    $entityManager->persist($topicPostLike);
                    $entityManager->flush();

                    // recalcul DownVote/Upvote
                    $newScore = $postLikeRepo->calcTopicPostScore($topicPost);
        
                    // JS FLASH: Votre downvote a été pris en compte
                    return new JsonResponse(['success' => true, 'newState' => 'downvoted', 'gameColor' => $topic->getGame()->getColor(), 'newScore' => $newScore]);   

                }
                // Si l'utilisateur a déjà downvoter le post: enleve le downvote, s'il l'a déjà upvoté, le downvote
                else {

                    if($postLikeRepo->findOneBy(['user'=>$this->getUser(), 'topicPost' =>$topicPost])->getState() == "downvote" ) {

                        $topicPostLike = $postLikeRepo->findOneBy(['user'=>$this->getUser(), 'topicPost' =>$topicPost]);

                        $postLikeRepo->remove($topicPostLike, true);
                        // $topicPostRepo->flush();

                        // recalcul DownVote/Upvote
                        $newScore = $postLikeRepo->calcTopicPostScore($topicPost);

                        // AJOUTER JS FLASH: $this->addFlash('success', 'Votre downvote a été retiré');
                        return new JsonResponse(['success' => true, 'newState' => 'notDownvoted', 'newScore' => $newScore]);   

                    }
                    else if($postLikeRepo->findOneBy(['user'=>$this->getUser(), 'topicPost' =>$topicPost])->getState() == "upvote" ) {

                        $topicPostLike = $postLikeRepo->findOneBy(['user'=>$this->getUser(), 'topicPost' =>$topicPost]);

                        $topicPostLike->setState("downvote");

                        $entityManager->persist($topicPostLike);
                        $entityManager->flush();

                        // recalcul DownVote/Upvote
                        $newScore = $postLikeRepo->calcTopicPostScore($topicPost);

                        // $this->addFlash('success', 'Votre upvote a été pris en compte');
                        return new JsonResponse(['success' => true, 'newState' => 'downvoted', 'gameColor' => $topic->getGame()->getColor(), 'newScore' => $newScore]);   

                    }
                }
            }
            else {
                return new JsonResponse(['success' => false, 'newState' => 'Vous ne pouvez pas downvoter vos commentaires', 'gameColor' => $topic->getGame()->getColor(), 'newScore' => $newScore]);   
    
            }
        }
        else {
            return new JsonResponse(['success' => false, 'case' => 'logIn']);
        }

    
    }




















    // Fermeture de topic par author (id: idTopic)  (voir pour asynch ajax ?)
    #[Route('/closeTopic/{id}', name: 'app_closeTopic')]
    public function closeTopicPost(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        $topicRepo = $entityManager->getRepository(Topic::class);

        $topic = $topicRepo->find($id);

        // Vérif si user est bien l'auteur du topic
        if ($this->getUser() == $topic->getUser()) {

            $topic->setStatus("closed");
            $entityManager->flush();

            $this->addFlash('success', 'Le topic a bien été fermé');
            return $this->redirectToRoute('app_topicDetail', ['id' => $id]); 
        }
        else {
            $this->addFlash('error', 'Vous devez être l\'auteur du topic ou admin pour pouvoir le fermer');
            return $this->redirectToRoute('app_topicDetail', ['id' => $id]); 
        }
    }

    // Réouverture du topic par author (id: idTopic)  (voir pour asynch ajax ?)
    #[Route('/openTopic/{id}', name: 'app_openTopic')]
    public function openTopicPost(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        $topicRepo = $entityManager->getRepository(Topic::class);

        $topic = $topicRepo->find($id);

        // Vérif si user est bien l'auteur du topic
        if ($this->getUser() == $topic->getUser()) {

            $topic->setStatus("open");
            $entityManager->flush();

            $this->addFlash('success', 'Le topic a bien été rouvert');
            return $this->redirectToRoute('app_topicDetail', ['id' => $id]); 
        }
        else {
            $this->addFlash('error', 'Vous devez être l\'auteur du topic ou admin pour pouvoir le rouvrir');
            return $this->redirectToRoute('app_topicDetail', ['id' => $id]); 
        }
    }



}
