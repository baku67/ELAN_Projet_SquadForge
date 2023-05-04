<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Form\SearchType;
use App\Form\TopicType;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\User;
use App\Entity\Topic;
use Doctrine\ORM\PersistentCollection;

use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GameController extends AbstractController
{

    // Liste des jeux par Genre
    #[Route('/games', name: 'app_games')]
    public function getGamesLists(EntityManagerInterface $entityManager, Request $request): Response
    {
        $gamesRepo = $entityManager->getRepository(Game::class);
        $genreRepo = $entityManager->getRepository(Genre::class);

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

        return $this->render('game/gameList.html.twig', [
            // 'games' => $allGames,
            // 'genres' => $allGenres,
            'fpsGames' => $fpsGames,
            'fpsGamesCount' => $fpsGamesCount,
            'indieGames' => $indieGames,
            'indieGamesCount' => $indieGamesCount,
            'battleRoyalGames' => $brGames,
            'brGamesCount' => $brGamesCount,
            // 'formSearch' => $form->createView(),
        ]);
    }


    // Détail d'un jeu (idGame)
    #[Route('/game/{id}', name: 'app_game')]
    public function getGameDetails(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        $gamesRepo = $entityManager->getRepository(Game::class);
        $game = $gamesRepo->find($id);

        // $gameTopics = $game->getTopics();

        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('t')
            ->from('App\Entity\Topic', 't')
            ->where('t.game = :game')
            ->setParameter('game', $game)
            ->orderBy('t.publish_date', 'DESC')
            ->setMaxResults(5); // set maximum number of results to 10
        $gameTopicsDesc = $queryBuilder->getQuery()->getResult();

        $gameTopicsCount = count($gameTopicsDesc);

        $gameGenre = $game->getGenre()->getName();

        $user = $this->getUser();

        if ($this->getUser()) {
            $isFavorited = $user->getFavoris()->contains($game);
        }
        else {
            $isFavorited = false;
        }



        // Form ajout Topic (Affichage et handleRequest)
        $topic = new Topic();
        $form = $this->createForm(TopicType::class, $topic);
        $form -> handleRequest($request);

        // Vérifs/Filtres
        if($form->isSubmitted()) {

            if($form->isValid()) {

                // Hydrataion du "Topic" a partir des données du form
                $topic = $form->getData();

                // Init de la publish_date du comment
                $topic->setPublishDate(new \DateTime());
                $topic->setGame($game);
                $topic->setUser($user);
                $topic->setStatus("ouvert");
                // En attendant le système de validation avant publication par un modo:
                $topic->setValidated("validated");
                
                // Récupération du titre
                $titleInputValue = $form->get('title')->getData();

                // Liste des mots du commentaires
                $words = str_word_count($titleInputValue, 1);

                // Décompte du nombre de mots dans la liste
                $wordCount = count($words);

                // Vérification du compte de mots
                if ($wordCount >= 5) {

                    // Modifs Base de données
                    $entityManager->persist($topic);
                    $entityManager->flush();

                    $this->addFlash('success', 'Le topic a bien été publié');
                    return $this->redirectToRoute('app_game', ['id' => $game->getId()]);

                } else {
                    
                    $this->addFlash('error', 'Le titre doit faire au minimum 5 mots !');
                    return $this->redirectToRoute('app_game', ['id' => $game->getId()]);
                }

            } 
            else {
                $this->addFlash('error', 'Les données envoyées ne sont pas valides');
                return $this->redirectToRoute('app_game', ['id' => $game->getId()]);
            }   
        }

        return $this->render('game/gameDetails.html.twig', [
            'formAddTopic' => $form->createView(),
            'game' => $game,
            'isFavorited' => $isFavorited,
            'gameGenre' => $gameGenre,
            'gameTopics' => $gameTopicsDesc,
            'gameTopicsCount' => $gameTopicsCount,
        ]);

    }


    // Liste des jeux d'un genre (idGenre)
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


    // Ajout du jeu au user (idUser) FAVORI
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

    // Retrait du jeu au user (idUser) FAVORI
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


    // Route utilisée par les requêtes ajax JS asynchrones (recherche %LIKE%) *Recherche*
    // voir function findBySearchQuery() /GameRepo
    #[Route("/search", name:"app_search")]
    public function searchAction(EntityManagerInterface $entityManager, UrlGeneratorInterface $router, Request $request)
    {
        $query = $request->query->get('query');
        $games = $entityManager
            ->getRepository(Game::class)
            ->findBySearchQuery($query);

        
        $results = [];
        foreach ($games as $game) {
            // Génération de la route gameDetail associée à chaque jeu trouvé
            $urlGameDetail = $router->generate('app_game', ["id" => $game->getId()]);

            $results[] = [
                'id' => $game->getId(),
                'title' => $game->getTitle(),
                'editor' => $game->getEditor(),
                'description' => $game->getDescription(),
                'publishDate' => $game->getPublishDate(),
                'genreId' => $game->getGenre(),
                'genreName' => $game->getGenre()->getName(),
                'color' => $game->getColor(),
                'logo' => $game->getLogo(),
                'urlGameDetail' => $urlGameDetail,
            ];
        }
        
        return new JsonResponse($results);
    }


    



}
