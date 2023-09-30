<?php

namespace App\Controller;

// Imagine: compression et resize Img Uploads (Médias)
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Gd\Imagine;

use Doctrine\ORM\EntityManagerInterface;
use App\Form\SearchType;
use App\Form\TopicType;
use App\Form\MediaType;
use App\Entity\Notification;
use App\Entity\Game;
use App\Entity\Censure;
use App\Entity\Genre;
use App\Entity\Notation;
use App\Entity\User;
use App\Entity\Topic;
use App\Entity\Media;
use App\Entity\Group;
use App\Entity\Report;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class GameController extends AbstractController
{

    private $csrfTokenManager;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->csrfTokenManager = $csrfTokenManager;
    }


    // Liste des jeux par Genre
    #[Route('/games', name: 'app_games')]
    public function getGamesLists(EntityManagerInterface $entityManager, Request $request): Response
    {
        $gamesRepo = $entityManager->getRepository(Game::class);
        $genreRepo = $entityManager->getRepository(Genre::class);
        $notifRepo = $entityManager->getRepository(Notification::class);
        $mediaRepo = $entityManager->getRepository(Media::class);
        $topicRepo = $entityManager->getRepository(Topic::class);
        $reportRepo = $entityManager->getRepository(Report::class);

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

        // Quand système de notation: trier par note 
        // Jeux par catégories
        $fpsGenre = $genreRepo->findBy(['name' => 'FPS']);
        $fpsGames = $gamesRepo->findBy(['genre' => $fpsGenre], ['publish_date' => 'DESC']);
        $fpsGamesCount = count($fpsGames);

        $mobaGenre = $genreRepo->findBy(['name' => 'MOBA']);
        $mobaGames = $gamesRepo->findBy(['genre' => $mobaGenre], ['publish_date' => 'DESC']);
        $mobaGamesCount = count($mobaGames);

        $indieGenre = $genreRepo->findBy(['name' => 'indie']);
        $indieGames = $gamesRepo->findBy(['genre' => $indieGenre], ['publish_date' => 'DESC']);
        $indieGamesCount = count($indieGames);

        $brGenre = $genreRepo->findBy(['name' => 'Battle Royal']);
        $brGames = $gamesRepo->findBy(['genre' => $brGenre], ['publish_date' => 'DESC']);
        $brGamesCount = count($brGames);

        return $this->render('game/gameList.html.twig', [
            'modoNotifValidationCount' => $modoNotifValidationCount,
            'modoNotifReportCount' => $modoNotifReportCount,
            'userNotifCount' => $userNotifCount,
            'mobaGames' => $mobaGames,
            'mobaGamesCount' => $mobaGamesCount,
            'fpsGames' => $fpsGames,
            'fpsGamesCount' => $fpsGamesCount,
            'indieGames' => $indieGames,
            'indieGamesCount' => $indieGamesCount,
            'battleRoyalGames' => $brGames,
            'brGamesCount' => $brGamesCount,
        ]);
    }


    // Détail d'un jeu (idGame)
    #[Route('/game/{slug}', name: 'app_game')]
    public function getGameDetails(EntityManagerInterface $entityManager, string $slug, Request $request): Response
    {
        $gamesRepo = $entityManager->getRepository(Game::class);
        $topicRepo = $entityManager->getRepository(Topic::class);
        $mediaRepo = $entityManager->getRepository(Media::class);
        $censureRepo = $entityManager->getRepository(Censure::class);
        $groupRepo = $entityManager->getRepository(Group::class);
        $notifRepo = $entityManager->getRepository(Notification::class);
        $reportRepo = $entityManager->getRepository(Report::class);

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

        $game = $gamesRepo->findOneBy(['slug' => $slug]);

        if( !is_null($game) ) {

            $gameGenre = $game->getGenre()->getName();
            $user = $this->getUser();
            $censures = $censureRepo->findAll();
            if($user) {
                $userGameGroups = $groupRepo->findGroupsByUserAndGame($user, $game);
            } 
            else {
                $userGameGroups = null;
            }

            $nbrOfTeams = count($groupRepo->findBy(["game" => $game, "status" => "public"]));

            // Check si relation "Favoris" (user_game), customQuery mieux ?
            if ($this->getUser()) {
                $isFavorited = $user->getFavoris()->contains($game);
            }
            else {
                $isFavorited = false;
            }


            // Récup de la note utilisateur
            $notationRepo = $entityManager->getRepository(Notation::class);
            
            if($this->getuser()) {
                $userGameNotation = $notationRepo->findOneBy(['user' => $user, 'game' => $game]);
            }
            else {
                $userGameNotation = null;
            }

            // Nombre de Notations user pour le jeu
            $nbrOfNotations = $notationRepo->countGameNotations($game);
            // Calc moyenne des notes (arrondi .5)
            $averageRating = ceil($notationRepo->getGameAverageNotation($game) * 2) / 2;

            // 5 derniers Topics du jeu
            $gameTopicsDesc = $topicRepo->findByGameMin($game);

            // Compte des topics du jeu
            $gameTopicsCount = $topicRepo->countGameTopics($game);
            

            // Form ajout Topic (Affichage et handleRequest)
            $topic = new Topic();
            $form = $this->createForm(TopicType::class, $topic);
            $form -> handleRequest($request);

            // Vérifs/Filtres
            if($form->isSubmitted()) {

                // refresh CSRF token (form_intention) (avoid multiple form submission)
                $this->csrfTokenManager->refreshToken("form_intention");

                if($this->getUser()) {

                    if( !($this->getUser()->isBanned()) && !($this->getUser()->IsMuted()) ) {

                        if($form->isValid()) {

                            // Vérif delai d'une heure par auteur par jeu
                            $currentDate = new \DateTime();
                            $oneHourAgo = $currentDate->sub(new \DateInterval('PT1H'));
                            $foundTopic = $topicRepo->verifyDelayPublish($this->getUser(), $game, $oneHourAgo);
                            if (!empty($foundTopic)) {
                                $this->addFlash('error', 'Vous ne pouvez publier ou proposer plus d\'un topic par heure et par jeu');
                                return $this->redirectToRoute('app_game', ['slug' => $game->getSlug()]);
                            }

                            // Hydrataion du "Topic" a partir des données du form
                            $topic = $form->getData();

                            // Init de la publish_date du comment
                            $topic->setPublishDate(new \DateTime());
                            $topic->setGame($game);
                            $topic->setUser($user);
                            $topic->setStatus("open");
                            // En attendant le système de validation avant publication par un modo:
                            $topic->setValidated("waiting");
                            
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

                                $this->addFlash('success', 'Le topic a été envoyé pour validation');
                                return $this->redirectToRoute('app_game', ['slug' => $game->getSlug()]);

                            } else {
                                
                                $this->addFlash('error', 'Le titre doit faire au minimum 5 mots !');
                                return $this->redirectToRoute('app_game', ['slug' => $game->getSlug()]);
                            }

                        } 
                        else {
                            $this->addFlash('error', 'Les données envoyées ne sont pas valides');
                            return $this->redirectToRoute('app_game', ['slug' => $game->getSlug()]);
                        }   
                    }
                    else {
                        $this->addFlash('error', 'Vous êtes actuellement réduit au silence (ou bannis), et ne pouvez rien publier');
                        return $this->redirectToRoute('app_game', ['slug' => $game->getSlug()]);
                    }
                }
                else {
                    $this->addFlash('error', 'Vous devez être connecté pour publier un topic');
                    return $this->redirectToRoute('app_login');
                }
            }
        }
        else {
            $this->addFlash('error', 'Le jeu demandé n\'existe pas ou plus.');
            return $this->redirectToRoute('app_home');
        }
        


        // 5 derniers médias du jeu
        $gameMediasDesc = $mediaRepo->findByGameMin($game);
        // Compte des médias du jeu
        $gameMediasCount = $mediaRepo->countGameMedias($game);


        // Form ajout Media (Affichage et handleRequest)
        $media = new Media();
        $form2 = $this->createForm(MediaType::class, $media);
        $form2 -> handleRequest($request);

        // Vérifs/Filtres
        if($form2->isSubmitted()) {

            // refresh CSRF token (form_intention) (avoid multiple form submission)
            $this->csrfTokenManager->refreshToken("form_intention");

            if($this->getUser()) {

                if( !$this->getUser()->isBanned() && !$this->getUser()->isMuted() ) {

                    if($form2->isValid()) {

                        // Vérif delai d'une heure par auteur par jeu
                        $currentDate = new \DateTime();
                        $oneHourAgo = $currentDate->sub(new \DateInterval('PT1H'));
                        $foundMedia = $mediaRepo->verifyDelayPublish($this->getUser(), $game, $oneHourAgo);
                        if (!empty($foundMedia)) {
                            $this->addFlash('error', 'Vous ne pouvez publier ou proposer plus d\'un média par heure et par jeu');
                            return $this->redirectToRoute('app_game', ['slug' => $game->getSlug()]);
                        }

                        // Hydrataion du "Media" a partir des données du form
                        $media = $form2->getData();

                        // Init de la publish_date du comment
                        $media->setPublishDate(new \DateTime());
                        $media->setGame($game);
                        $media->setUser($user);
                        $media->setStatus("open");
                        // En attendant le système de validation avant publication par un modo:
                        $media->setValidated("waiting");
                        
                        // Récupération du titre
                        $titleInputValue = $form2->get('title')->getData();

                        // Récupération de l'image du média
                        $mediaImg = $form2->get('url')->getData();

                        // Vérification de l'extension (.png, .jpg, .jpeg, .gif)
                        $fileExt = $mediaImg->getClientOriginalExtension();
                        if ($fileExt != "png" && $fileExt != "jpg" && $fileExt != "jpeg" && $fileExt != "gif") {
                            $this->addFlash('error', 'Le format "' . $fileExt . '" n\'est pas supporté');
                            return $this->redirectToRoute('app_game', ['slug' => $game->getSlug()]);
                        }

                        // Vérification de la taille du fichier + Vérif que c'est bien un fichier qui est uploadé (pour pouvoir utiliser getSize())
                        // Attention: vérifications Front en amont "maxFileSize" dans "gameDetails.html.twig"
                        $maxFileSize = 10 * 1024 * 1024; /* (10MB) */
                        if ($mediaImg instanceof UploadedFile && $mediaImg->getSize() > $maxFileSize) {
                            $this->addFlash('error', 'Le fichier est trop volumineux');
                            return $this->redirectToRoute('app_game', ['slug' => $game->getSlug()]);
                        }

                        // Compression et Resize (GIF/PNG ou JPG) avec library "Imagine"
                        // $imagine = new Imagine();

                        // if (in_array($fileExt, ['gif', 'png'], true)) {
                        //     $image = $imagine->open($mediaImg->getPathname());
                        //     // $image->resize(new Box(800, 600));
                        //     $image->save($pathToSave, ['png_compression_level' => 9]);
                        // }
                        // else {
                        //     $image = $imagine->open($mediaImg->getPathname());
                        //     // $image->resize(new Box(800, 600));
                        //     $image->save($pathToSave, ['jpeg_quality' => 80]);
                        // }


                        $genImgName = $this->generateCustomFileName() . "." . $fileExt;

                        try {
                            $mediaImg->move(
                            // $image->move(
                                $this->getParameter('upload_directory'),
                                $genImgName
                            );
                        } catch (FileException $e) {
                            $this->addFlash('error', 'Il y a eu un problème lors de l\'upload du fichier');
                            return $this->redirectToRoute('app_game', ['slug' => $game->getSlug()]);
                        }
                        $media->setUrl($genImgName);


                        // Liste des mots du commentaires
                        $words = str_word_count($titleInputValue, 1);
                        // Décompte du nombre de mots dans la liste
                        $wordCount = count($words);
                        // Vérification du compte de mots
                        if ($wordCount >= 5) {

                            // Modifs Base de données
                            $entityManager->persist($media);
                            $entityManager->flush();

                            $this->addFlash('success', 'Le média a bien été envoyé pour validation');
                            return $this->redirectToRoute('app_game', ['slug' => $game->getSlug()]);

                        } else {
                            
                            $this->addFlash('error', 'Le titre doit faire au minimum 5 mots !');
                            return $this->redirectToRoute('app_game', ['slug' => $game->getSlug()]);
                        }

                    } 
                    else {
                        $this->addFlash('error', 'Les données envoyées ne sont pas valides');
                        return $this->redirectToRoute('app_game', ['slug' => $game->getSlug()]);
                    }   
                } 
                else {
                    $this->addFlash('error', 'Vous êtes actuellement réduit au silence (ou bannis), et ne pouvez rien publier');
                    return $this->redirectToRoute('app_game', ['slug' => $game->getSlug()]);
                }
            }
            else {
                $this->addFlash('error', 'Vous devez être connecté pour publier un média');
                return $this->redirectToRoute('app_login');
            }
        }
        

        return $this->render('game/gameDetails.html.twig', [
            'modoNotifValidationCount' => $modoNotifValidationCount,
            'modoNotifReportCount' => $modoNotifReportCount,
            'userNotifCount' => $userNotifCount,
            'formAddTopic' => $form->createView(),
            'formAddMedia' => $form2->createView(),
            'game' => $game,
            'isFavorited' => $isFavorited,
            'gameGenre' => $gameGenre,
            'gameTopics' => $gameTopicsDesc,
            'gameTopicsCount' => $gameTopicsCount,
            'userGameNotation' => $userGameNotation,
            'nbrOfNotations' => $nbrOfNotations,
            'averageRating' => $averageRating,
            'gameMedias' => $gameMediasDesc,
            'gameMediasCount' => $gameMediasCount,
            'censures' => $censures,
            'nbrOfTeams' => $nbrOfTeams,
            'userGameGroups' => $userGameGroups,
        ]);

    }

    private function generateCustomFileName(): string
    {
        // Implement your custom logic to generate the file name
        // For example, you can use a combination of timestamp and a unique identifier
        return uniqid() . '_' . time();
    }


    // Liste des jeux d'un genre (slugGenre)
    #[Route('/genreGames/{slug}', name: 'app_genreGames')]
    public function getGenreGames(EntityManagerInterface $entityManager, string $slug): Response
    {
        $gamesRepo = $entityManager->getRepository(Game::class);
        $notifRepo = $entityManager->getRepository(Notification::class);
        $mediaRepo = $entityManager->getRepository(Media::class);
        $topicRepo = $entityManager->getRepository(Topic::class);
        $reportRepo = $entityManager->getRepository(Report::class);

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

        $genreRepo = $entityManager->getRepository(Genre::class);

        $genreId = $genreRepo->findOneBy(['slug' => $slug])->getId();

        $genreGames = $gamesRepo->findBy(['genre' => $genreId]);

        $genreName = $genreRepo->findOneBy(['slug' => $slug])->getName();

        return $this->render('game/genreGameList.html.twig', [
            'modoNotifValidationCount' => $modoNotifValidationCount,
            'modoNotifReportCount' => $modoNotifReportCount,
            'userNotifCount' => $userNotifCount,
            'genreGames' => $genreGames,
            'genreName' => $genreName,
        ]);

    }


    // MaJ notation d'un (idGame) (rating) ASYNC
    #[Route('/updateNotation/{id}/{rating}', name: 'app_updateNotation')]
    public function updateGameUserNotation(EntityManagerInterface $entityManager, int $id, int $rating, UrlGeneratorInterface $router, Request $request): Response
    {

        if( $this->getUser() ) {

            $gameRepo = $entityManager->getRepository(Game::class);

            $user = $this->getUser();
            $game = $gameRepo->find($id);

            $notationRepo = $entityManager->getRepository(Notation::class);
            $notation = $notationRepo->findOneBy(['user' => $user, 'game' => $game]);

            // S'il n'y a pas de notation pour ce user et ce jeu, la créer
            if (!$notation) {
                $notation = new Notation();
                $notation->setUser($user);
                $notation->setGame($game);
                $notation->setNote($rating);
            }
            else {
                // HS: Si la notation existante est la même (=reclick pour annulation)
                if ($notation->getNote() == $rating) {
                    $entityManager->remove($notation);
                    $entityManager->flush();

                    // Calc moyenne des notes (arrondi .5)
                    $averageRating = ceil($notationRepo->getGameAverageNotation($game) * 2) / 2;
                    // Nombre de Notations user pour le jeu
                    $nbrOfNotations = $notationRepo->countGameNotations($game);
                    
                    return new JsonResponse(['success' => true, 'eraseNote' => true, 'newAverageNote' => $averageRating, 'newVoteCount' => $nbrOfNotations]);  
                }
                // Si changement de note:
                else {
                    $notation->setNote($rating);
                }
            }

            $entityManager->persist($notation);
            $entityManager->flush();

            // Calc moyenne des notes (arrondi .5)
            $averageRating = ceil($notationRepo->getGameAverageNotation($game) * 2) / 2;
            // Nombre de Notations user pour le jeu
            $nbrOfNotations = $notationRepo->countGameNotations($game);
            
            return new JsonResponse(['success' => true, 'newAverageNote' => $averageRating, 'newVoteCount' => $nbrOfNotations]);  

        }
        else {
            throw new AccessDeniedHttpException('You must be authenticated to add or update a notation.');
        }
    }







    // Async toggle Favoris Bouton (id Game)
    #[Route('/game/toggleGameFav/{id}', name: 'app_toggleGameFav')]
    public function toggleGameFav(EntityManagerInterface $entityManager, int $id): Response
    {
        if ($this->getUser()) {
            
            $gamesRepo = $entityManager->getRepository(Game::class);
            $userRepo = $entityManager->getRepository(User::class);
            $user = $this->getUser();
            $game = $gamesRepo->find($id);


            $userLikedGames = $user->getFavoris(); // userfavoris Class::Games collection

            // Si relation existe: favorisé -> unFavorise
            if ($userLikedGames->contains($game)) {
                
                $user->removeFavori($game);
                $entityManager->flush();

                return new JsonResponse(['success' => true, 'newState' => 'notFavorited']);

            }
            // Sinon: favorise
            else {

                $user->addFavori($game);
                $entityManager->flush();

                return new JsonResponse(['success' => true, 'newState' => 'favorited']);

            }

        }
        else {
            return new JsonResponse(['success' => false]);
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
            $urlGameDetail = $router->generate('app_game', ["slug" => $game->getSlug()]);

            // TODO: sérialization plus propre 
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
                'subBanner' => $game->getSubBanner(),
                'slug' => $game->getSlug(),
            ];
        }
        
        return new JsonResponse($results);
    }


    
    // Page tournoi en construction
    #[Route("/{gameSlug}/tournaments", name:"app_tournaments")]
    public function tournamentsPage(EntityManagerInterface $entityManager, string $gameSlug, Request $request)
    {

        $notifRepo = $entityManager->getRepository(Notification::class);
        $mediaRepo = $entityManager->getRepository(Media::class);
        $topicRepo = $entityManager->getRepository(Topic::class);
        $reportRepo = $entityManager->getRepository(Report::class);

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
        


        return $this->render('game/tournaments.html.twig', [
            'modoNotifValidationCount' => $modoNotifValidationCount,
            'modoNotifReportCount' => $modoNotifReportCount,
            'userNotifCount' => $userNotifCount,

        ]);
    }

}
