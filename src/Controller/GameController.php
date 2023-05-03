<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Form\SearchType;
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
    public function getGamesLists(EntityManagerInterface $entityManager, Request $request): Response
    {
        $gamesRepo = $entityManager->getRepository(Game::class);
        $genreRepo = $entityManager->getRepository(Genre::class);

        // $allGames = $gamesRepo->findBy([], ['publish_date' => 'DESC']);
        // $allGenres = $genreRepo->findAll(); 

        // Quand système de notation: trier par note 
        // Jeux par catégories
        $fpsGenre = $genreRepo->findBy(['name' => 'FPS']);
        $fpsGames = $gamesRepo->findBy(['genre' => $fpsGenre], ['publish_date' => 'DESC']);
        $fpsGamesCount = count($fpsGames);

        $indieGenre = $genreRepo->findBy(['name' => 'indie']);
        $indieGames = $gamesRepo->findBy(['genre' => $indieGenre], ['publish_date' => 'DESC']);
        $indieGamesCount = count($indieGames);

        $brGenre = $genreRepo->findBy(['name' => 'Battle Royal']);
        $brGames = $gamesRepo->findBy(['genre' => $brGenre], ['publish_date' => 'DESC']);
        $brGamesCount = count($brGames);


        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $query = $form->getData()['query'];
            $games = $entityManager
                ->getRepository(Game::class)
                ->findBySearchQuery($query);
            // ...
            // Si input recherche, vue résultat différrente
            return $this->render('game/searchResult.html.twig', [
                'form' => $form->createView(),
                'searchGames' => $games ?? null,
                'query' => $query,
                'formSearch' => $form->createView(),
            ]);
        }


        return $this->render('game/gameList.html.twig', [
            // 'games' => $allGames,
            // 'genres' => $allGenres,
            'fpsGames' => $fpsGames,
            'fpsGamesCount' => $fpsGamesCount,
            'indieGames' => $indieGames,
            'indieGamesCount' => $indieGamesCount,
            'battleRoyalGames' => $brGames,
            'brGamesCount' => $brGamesCount,
            'formSearch' => $form->createView(),
        ]);
    }


    #[Route('/game/{id}', name: 'app_game')]
    public function getGameDetails(EntityManagerInterface $entityManager, int $id): Response
    {
        $gamesRepo = $entityManager->getRepository(Game::class);
        $game = $gamesRepo->find($id);

        $gameGenre = $game->getGenre()->getName();

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
            'gameGenre' => $gameGenre,
        ]);

    }


    #[Route('/genreGames/{id}', name: 'app_genreGames')]
    public function getGenreGames(EntityManagerInterface $entityManager, int $id): Response
    {
        $gamesRepo = $entityManager->getRepository(Game::class);
        $genreGames = $gamesRepo->findBy(['genre' => $id]);

        $genreRepo = $entityManager->getRepository(Genre::class);
        $genreName = $genreRepo->find($id)->getName();

        return $this->render('game/genreGameList.html.twig', [
            'genreGames' => $genreGames,
            'genreName' => $genreName,
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
