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


    // id: idTopic
    #[Route('/topicDetail/{id}', name: 'app_topicDetail')]
    public function getTopicDetail(EntityManagerInterface $entityManager, int $id): Response
    {

        $topicRepo = $entityManager->getRepository(Topic::class);
        $topic = $topicRepo->find($id);

        $topicGame = $topic->getGame();

        return $this->render('topic/topicDetail.html.twig', [
            'topic' => $topic,
            'game' => $topicGame,
        ]);

    }

}
