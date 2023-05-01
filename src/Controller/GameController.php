<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Game;
use App\Entity\Genre;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class GameController extends AbstractController
{
    #[Route('/games', name: 'app_games')]
    public function getGamesLists(EntityManagerInterface $entityManager): Response
    {

        $gamesRepo = $entityManager->getRepository(Game::class);

        // Quand système de notation: trier par note 
        $allGames = $gamesRepo->findBy([], ['publish_date' => 'DESC']);

        return $this->render('game/index.html.twig', [
            'games' => $allGames,
        ]);
    }
}
