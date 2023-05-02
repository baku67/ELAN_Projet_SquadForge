<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\User;
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

        $user = $this->getUser();

        if ($this->getUser()) {
            $isFavorited = $user->getFavoris()->contains($game);
        }
        else {
            $isFavorited = false;
        }

        return $this->render('game/gameDetails.html.twig', [
            'game' => $game,
            'isFavorited' => $isFavorited,
        ]);

    }


    #[Route('/game/addfav/{id}', name: 'app_addfav')]
    public function addGameToFav(EntityManagerInterface $entityManager, int $id): Response
    {
        if ($this->getUser()) {

            $gamesRepo = $entityManager->getRepository(Game::class);
            $game = $gamesRepo->find($id);

            $this->getUser()->addFavori($game);

            $entityManager->persist($this->getUser());
            $entityManager->flush();

            return $this->redirectToRoute('app_game', ['id' => $id]);

        }
        else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/game/removefav/{id}', name: 'app_removefav')]
    public function removeGameToFav(EntityManagerInterface $entityManager, int $id): Response
    {
        if ($this->getUser()) {

            $gamesRepo = $entityManager->getRepository(Game::class);
            $game = $gamesRepo->find($id);

            $this->getUser()->removeFavori($game);

            // $entityManager->persist($this->getUser());
            $entityManager->flush();

            return $this->redirectToRoute('app_game', ['id' => $id]);

        }
        else {
            return $this->redirectToRoute('app_login');
        }
    }


}
