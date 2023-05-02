<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Game;
use App\Entity\Genre;
use Doctrine\ORM\PersistentCollection;

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
        $genreRepo = $entityManager->getRepository(Genre::class);

        // $allGames = $gamesRepo->findBy([], ['publish_date' => 'DESC']);
        // $allGenres = $genreRepo->findAll(); 

        // Quand système de notation: trier par note 
        // Jeux par catégories
        $fpsGenre = $genreRepo->findBy(['name' => 'FPS']);
        $fpsGames = $gamesRepo->findBy(['genre' => $fpsGenre], ['publish_date' => 'DESC']);

        $indieGenre = $genreRepo->findBy(['name' => 'indie']);
        $indieGames = $gamesRepo->findBy(['genre' => $indieGenre], ['publish_date' => 'DESC']);

        $brGenre = $genreRepo->findBy(['name' => 'Battle Royal']);
        $brGames = $gamesRepo->findBy(['genre' => $brGenre], ['publish_date' => 'DESC']);

        return $this->render('game/gameList.html.twig', [
            // 'games' => $allGames,
            // 'genres' => $allGenres,
            'fpsGames' => $fpsGames,
            'indieGames' => $indieGames,
            'battleRoyalGames' => $brGames,
        ]);
    }


    #[Route('/game/{id}', name: 'app_game')]
    public function getGameDetails(EntityManagerInterface $entityManager, int $id): Response
    {
        $gamesRepo = $entityManager->getRepository(Game::class);

        $game = $gamesRepo->find($id);

        return $this->render('game/gameDetails.html.twig', [
            'game' => $game,
        ]);

    }


}
