<?php

namespace App\Controller;

use App\Entity\Topic;
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
    public function getTopicDetail(EntityManagerInterface $entityManager, int $id): Response
    {

        $topicRepo = $entityManager->getRepository(Topic::class);
        $topic = $topicRepo->find($id);

        $topicGame = $topic->getGame();

        $topicPosts = $topic->getTopicPosts();

        return $this->render('topic/topicDetail.html.twig', [
            'topic' => $topic,
            'game' => $topicGame,
            'topicPosts' => $topicPosts
        ]);

    }











}
