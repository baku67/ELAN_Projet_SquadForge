<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Entity\TopicPost;
use App\Form\TopicPostType;
use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TopicController extends AbstractController
{
    #[Route('/topic', name: 'app_topic')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $topicRepo = $entityManager->getRepository(Topic::class);
        $gameTopics = $topicRepo->findAll();

        return $this->render('topic/index.html.twig', [
        ]);
    }

    // Tout les Topics du jeu et tout les Topics globaux (pour pouvoir switch le filtre sur la vue)
    #[Route('/allTopics/{gameIdFrom}', name: 'app_allTopics')]
    public function getAllTopics(EntityManagerInterface $entityManager, int $gameIdFrom): Response
    {
        // if gameIdFrom == "home" : On envoi pas le jeu ?

        $gameRepo = $entityManager->getRepository(Game::class);
        $gameFrom = $gameRepo->find($gameIdFrom);

        $topicRepo = $entityManager->getRepository(Topic::class);

        // Liste de tous les Topics du jeu (le jeu d'où vient la requete)
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('t')
            ->from('App\Entity\Topic', 't')
            ->where('t.game = :game')
            ->setParameter('game', $gameFrom)
            ->orderBy('t.publish_date', 'DESC');
        $gameTopicsDesc = $queryBuilder->getQuery()->getResult();

        $gameTopicsCount = count($gameTopicsDesc);

        // Liste de tous les Topics 
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('t')
            ->from('App\Entity\Topic', 't')
            ->orderBy('t.publish_date', 'DESC');
        $allTopicsDesc = $queryBuilder->getQuery()->getResult();

        $allTopicsCount = count($allTopicsDesc);

        if ($gameIdFrom != "home") {
            $from = "game";
        }
        else {
            $from = "home";
        }


        return $this->render('topic/index.html.twig', [
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

        // Liste de tous les Topics 
        // Todo: findBy plutot
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('t')
            ->from('App\Entity\Topic', 't')
            ->orderBy('t.publish_date', 'DESC');
        $allTopicsDesc = $queryBuilder->getQuery()->getResult();

        $allTopicsCount = count($allTopicsDesc);


        return $this->render('topic/allTopicsGlobal.html.twig', [
            'allTopicsDesc' => $allTopicsDesc,
            'allTopicsCount' => $allTopicsCount,
        ]);
    }



    // Tout les Topics de l'user connecté (pas d'UserId passé donc) (from profil)
    #[Route('/allTopicsUser', name: 'app_allTopicsUser')]
    public function getAllTopicsUser(EntityManagerInterface $entityManager): Response
    {
        $topicRepo = $entityManager->getRepository(Topic::class);

        $userTopicsDesc = $topicRepo->findBy(['user' => $this->getUser()], ['publish_date' => 'DESC']);
        $userTopicsCount = count($userTopicsDesc);

        return $this->render('user/allTopicsUser.html.twig', [
            'userTopics' => $userTopicsDesc,
            'userTopicsCount' => $userTopicsCount,
        ]);

    }


    // id: idTopic
    #[Route('/topicDetail/{id}', name: 'app_topicDetail')]
    public function getTopicDetail(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {

        $topicRepo = $entityManager->getRepository(Topic::class);
        $topicPostRepo = $entityManager->getRepository(TopicPost::class);

        $topic = $topicRepo->find($id);

        $topicGame = $topic->getGame();

        // A remplacer par customQuery: triés par nbr d'upvote et sinon par publishDate (récent en haut) [différent d'un chat]
        // + On cherche uniquement les posts qui ne répondent pas à un post (reponseId = null)
        // (les réponses au post s'afficheront avec ajax au click sur le post)
        $topicPosts = $topicPostRepo->findBy(['topic' => $topic, "responseId" => null], ['publish_date' => 'DESC']);



        // Form de publication de post sur un topic
        $topicPost = new TopicPost();
        $form = $this->createForm(TopicPostType::class, $topicPost);
        $form -> handleRequest($request);

        // Vérifs/Filtres
        if($form->isSubmitted()) {

            if($this->getUser()) {

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
                    $this->addFlash('error', 'Les données envoyées ne sont pas valides');
                    return $this->redirectToRoute('app_topicDetail', ['id' => $topic->getId()]);
                }   
            }
            else {
                $this->addFlash('error', 'Vous devez être connecté pour publier un post');
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('topic/topicDetail.html.twig', [
            'formAddTopicPost' => $form->createView(),
            'topic' => $topic,
            'game' => $topicGame,
            'topicPosts' => $topicPosts
        ]);

    }











}
