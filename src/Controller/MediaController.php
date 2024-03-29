<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\Media;
use App\Entity\Topic;
use App\Entity\Censure;
use App\Entity\MediaPost;
use App\Entity\MediaPostLike;
use App\Entity\Game;
use App\Entity\Report;
use App\Entity\ReportMotif;
use App\Form\MediaType;
use App\Form\MediaPostType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class MediaController extends AbstractController
{

    private $notifController;
    private $csrfTokenManager;

    public function __construct(NotificationController $notifController, CsrfTokenManagerInterface $csrfTokenManager) 
    {
        $this->notifController = $notifController;
        $this->csrfTokenManager = $csrfTokenManager;
    }


    // Listing tous les Médias du jeu (max 20 Repo)
    #[Route('/allMedias/{gameIdFrom}', name: 'app_allMedias')]
    public function getGameMedias(EntityManagerInterface $entityManager, int $gameIdFrom, Request $request): Response
    {
        $gameRepo = $entityManager->getRepository(Game::class);
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

        $gameFrom = $gameRepo->find($gameIdFrom);

        // Dernier Médias du jeu (max 20 Repo) (+ count DQL total)
        $mediaRepo = $entityManager->getRepository(Media::class);
        $gameMediasDesc = $mediaRepo->findGameLastMedias($gameFrom);
        $gameMediasCount = $mediaRepo->countGameMedias($gameFrom);

        // Form ajout Media (Affichage et handleRequest)
        $media = new Media();
        $form2 = $this->createForm(MediaType::class, $media);
        $form2 -> handleRequest($request);

        // Vérifs/Filtres
        if($form2->isSubmitted()) {

            // refresh CSRF token (form_intention) (avoid multiple form submission)
            $this->csrfTokenManager->refreshToken("form_intention");

            if($this->getUser()) {

                if($form2->isValid()) {

                    // Vérif delai d'une heure par média par auteur par jeu
                    $currentDate = new \DateTime();
                    $oneHourAgo = $currentDate->sub(new \DateInterval('PT1H'));
                    $foundMedia = $mediaRepo->verifyDelayPublish($this->getUser(), $gameFrom, $oneHourAgo);
                    if (!empty($foundMedia)) {
                        $this->addFlash('error', 'Vous ne pouvez publier ou proposer plus d\'un média par heure et par jeu');
                        return $this->redirectToRoute('app_game', ['slug' => $gameFrom->getSlug()]);
                    }

                    // Hydrataion du "Media" a partir des données du form
                    $media = $form2->getData();

                    // Init de la publish_date du comment
                    $media->setPublishDate(new \DateTime());
                    $media->setGame($gameFrom);
                    $media->setUser($this->getUser());
                    $media->setStatus("ouvert");
                    // En attendant le système de validation avant publication par un modo:
                    $media->setValidated("waiting");
                    
                    // Récupération du titre
                    $titleInputValue = $form2->get('title')->getData();

                    // Récupération de l'image du média
                    $mediaImg = $form2->get('url')->getData();

                    // Vérification de l'extension (.png, .jpg, .jpeg, .gif)
                    $fileExt = $mediaImg->getClientOriginalExtension();
                    if ($fileExt != "png" && $fileExt != "PNG" && $fileExt != "jpg" && $fileExt != "JPG" && $fileExt != "jpeg" && $fileExt != "gif") {
                        $this->addFlash('error', 'Le format "' . $fileExt . '" n\'est pas supporté');
                        return $this->redirectToRoute('app_game', ['slug' => $gameFrom->getSlug()]);
                    }
                    // Vérification de la taille du fichier + Vérif que c'est bien un fichier qui est uploadé (pour pouvoir utiliser getSize())
                    $maxFileSize = 5 * 1024 * 1024; /* (5MB) */
                    if ($mediaImg instanceof UploadedFile && $mediaImg->getSize() > $maxFileSize) {
                        $this->addFlash('error', 'Le fichier est trop volumineux');
                        return $this->redirectToRoute('app_game', ['slug' => $gameFrom->getSlug()]);
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
                        return $this->redirectToRoute('app_game', ['slug' => $gameFrom->getSlug()]);
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
                        return $this->redirectToRoute('app_game', ['slug' => $gameFrom->getSlug()]);

                    } else {
                        
                        $this->addFlash('error', 'Le titre doit faire au minimum 5 mots !');
                        return $this->redirectToRoute('app_game', ['slug' => $gameFrom->getSlug()]);
                    }
                } 
                else {
                    $this->addFlash('error', 'Pas de vulgarités pour un titre');
                    return $this->redirectToRoute('app_game', ['slug' => $gameFrom->getSlug()]);
                }   
            }
            else {
                $this->addFlash('error', 'Vous devez être connecté pour publier un média');
                return $this->redirectToRoute('app_login');
            }
        }

        if ($gameIdFrom != "home") {
            $from = "game";
        }
        else {
            $from = "home";
        }

        return $this->render('media/gameMedias.html.twig', [
            'modoNotifValidationCount' => $modoNotifValidationCount,
            'modoNotifReportCount' => $modoNotifReportCount,
            'userNotifCount' => $userNotifCount,
            'formAddMedia' => $form2->createView(),
            'gameMediasDesc' => $gameMediasDesc,
            'gameMediasCount' => $gameMediasCount,
            'gameFrom' => $gameFrom,
            'from' => $from,
        ]);
    }




    private function generateCustomFileName(): string
    {
        // Implement your custom logic to generate the file name
        // For example, you can use a combination of timestamp and a unique identifier
        return uniqid() . '_' . time();
    }




    // Médias Details (slug: slugMedia) + Form PostMedia
    #[Route('/mediaDetail/{slug}/{notifId}', name: 'app_mediaDetail', defaults: ['notifId' => null])]
    public function getMediaDetail(EntityManagerInterface $entityManager, Request $request, string $slug, int $notifId = null): Response
    {
        $mediaRepo = $entityManager->getRepository(Media::class);
        $notifRepo = $entityManager->getRepository(Notification::class);
        $mediaRepo = $entityManager->getRepository(Media::class);
        $topicRepo = $entityManager->getRepository(Topic::class);
        $reportMotifRepo = $entityManager->getRepository(ReportMotif::class);
        $reportRepo = $entityManager->getRepository(Report::class);

        // Si page vient de notifId, passe la notif en "clicked"
        if (!is_null($notifId)) {
            $notifFrom = $notifRepo->find($notifId);
            $notifFrom->setClicked(true);
            $entityManager->persist($notifFrom);
            $entityManager->flush();
        }

        // Liste select Options de signalements:
        $reportMotifs = $reportMotifRepo->findAll();

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

        $media = $mediaRepo->findOneBy(['slug' => $slug]);

        if (!is_null($media) && $media->getValidated() == "validated") {

            $mediaPostRepo = $entityManager->getRepository(MediaPost::class);
            $censureRepo = $entityManager->getRepository(Censure::class);
    
            $censures = $censureRepo->findAll();
    
            $mediaGame = $media->getGame();
    
            // A remplacer par customQuery: triés par nbr d'upvote et sinon par publishDate (récent en haut) [différent d'un chat]
            // + On cherche uniquement les posts qui ne répondent pas à un post (parent = null (nullable))
            // (les réponses au post s'afficheront avec ajax au click sur le post)
    
            $mediaPosts = $mediaPostRepo->findBy(['media' => $media], ['publish_date' => 'DESC']);
    
            // Form de publication de post sur un media
            $mediaPost = new MediaPost();
            $form = $this->createForm(MediaPostType::class, $mediaPost);
            $form -> handleRequest($request);
    
            // Vérifs/Filtres
            if($form->isSubmitted()) {
    
                // Vérif connecté pour poster un MediaPost
                if($this->getUser()) {

                    // Vérif si User Mute ou Ban 
                    if( !($this->getUser()->isBanned()) && !($this->getUser()->isMuted())) {

                        // Vérification si le media est ouvert
                        if ($media->getStatus() == "open") {

                            // Vérif si post vide
                            if(strlen($mediaPost->getText()) > 0) {
                            
                                if($form->isValid()) {

                                    // Vérif delai de 10min par topic par auteur par jeu
                                    $currentDate = new \DateTime();
                                    $oneHourAgo = $currentDate->sub(new \DateInterval('PT10M'));
                                    $foundMediaPost = $mediaPostRepo->verifyDelayPublish($this->getUser(), $media, $oneHourAgo);
                                    if (!empty($foundMediaPost)) {
                                        $this->addFlash('error', 'Vous ne pouvez publier ou proposer plus d\'un commentaire par média par 10 minutes');
                                        return $this->redirectToRoute('app_mediaDetail', ['slug' => $media->getSlug()]);
                                    }
            
                                    // Hydrataion du "MediaPost" a partir des données du form
                                    $mediaPost = $form->getData();
                
                                    // Init de la publish_date du comment
                                    $mediaPost->setPublishDate(new \DateTime());
                                    $mediaPost->setUser($this->getUser());
                                    $mediaPost->setMedia($media);
                                    
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
                                        $entityManager->persist($mediaPost);
                                        $entityManager->flush();
                
                                        $this->addFlash('success', 'Le post a bien été publié');
                                        return $this->redirectToRoute('app_mediaDetail', ['slug' => $media->getSlug()]);
                                    // } else {
                                        
                                    //     $this->addFlash('error', 'Le titre doit faire au minimum 5 mots !');
                                    //     return $this->redirectToRoute('app_game', ['slug' => $game->getSlug()]);
                                    // }
                                } 
                                else {
                                    $this->addFlash('error', 'Les données envoyées ne sont pas valides');
                                    return $this->redirectToRoute('app_mediaDetail', ['slug' => $media->getSlug()]);
                                }   
                            }
                            else {
                                $this->addFlash('error', 'Le commentaire ne peut pas être vide');
                                return $this->redirectToRoute('app_mediaDetail', ['slug' => $media->getSlug()]);
                            }
                        }
                        else {
                            $this->addFlash('error', 'Le media a été fermé, vous ne pouvez plus le commenter.');
                            return $this->redirectToRoute('app_mediaDetail', ['slug' => $media->getSlug()]);
                        }
                    }
                    else {
                        $this->addFlash('error', 'Vous êtes actuellement réduit au silence (ou bannis), et ne pouvez rien publier');
                        return $this->redirectToRoute('app_mediaDetail', ['slug' => $media->getSlug()]);
                    }
                }
                else {
                    $this->addFlash('error', 'Vous devez être connecté pour publier un post');
                    return $this->redirectToRoute('app_login');
                }
            }
            return $this->render('media/mediaDetail.html.twig', [
                'reportMotifs' => $reportMotifs,
                'modoNotifValidationCount' => $modoNotifValidationCount,
                'modoNotifReportCount' => $modoNotifReportCount,
                'userNotifCount' => $userNotifCount,
                'formAddMediaPost' => $form->createView(),
                'media' => $media,
                'game' => $mediaGame,
                'mediaPosts' => $mediaPosts,
                'censures' => $censures,
            ]);
        }
        else {
            $this->addFlash('error', 'Le média est en attente ou refusé par la modération, ou bien n\'existe plus');
            return $this->redirectToRoute('app_home');
        }
    }



    // Suppression Media (id: media) (auteur)
    #[Route('/deleteSelfMedia/{idMedia}', name: 'app_deleteSelfMedia')]
    public function deleteSelfMedia(EntityManagerInterface $entityManager, int $idMedia, Request $request): Response
    {
        $mediaRepo = $entityManager->getRepository(Media::class);
        $media = $mediaRepo->find($idMedia);

        // Vérif auteur
        if ($this->getUser() && $this->getUser() == $media->getUser()) {

            $mediaRepo->remove($media, true);

            $this->addFlash('success', 'Votre média a été supprimé');
            return $this->redirectToRoute('app_user');
        }
        else {
            $this->addFlash('error', 'Vous devez être connecté et l\'auteur du média pour pouvoir le supprimer');
            return $this->redirectToRoute('app_user');
        }
    }



    // Suppression commentaire (id: post) (auteur)
    #[Route('/deleteMediaPost/{idPost}', name: 'app_deleteMediaPost')]
    public function deleteMediaPost(EntityManagerInterface $entityManager, int $idPost, Request $request): Response
    {
        $mediaPostRepository = $entityManager->getRepository(MediaPost::class);
        $mediaPost = $mediaPostRepository->find($idPost);

        // Vérif auteur
        if ($this->getUser() && $this->getUser() == $mediaPost->getUser()) {

            // $mediaPostRepository->remove($mediaPost, true);
            // TODO anonymisation ou remplacé par censure ?
            $mediaPost->setText('Le commentaire a été supprimé');
            $mediaPost->setUser(NULL);

            $entityManager->persist($mediaPost);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commentaire a été supprimé');
            return $this->redirectToRoute('app_mediaDetail', ['slug' => $mediaPost->getMedia()->getSlug()]);

        }
        else {
            $this->addFlash('error', 'Vous devez être connecté et l\'auteur du post pour pouvoir le supprimer');
            return $this->redirectToRoute('app_mediaDetail', ['slug' => $mediaPost->getMedia()->getSlug()]);
        }
    }







    // Tout les Medias globaux (from /homePage)
    #[Route('/allMediasGlobal', name: 'app_allMediasGlobal')]
    public function getAllMediasGlobal(EntityManagerInterface $entityManager): Response
    {
        $mediaRepo = $entityManager->getRepository(Media::class);
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

        // Tous les médias (max 50 Repo) + count DQL total
        $allMediasDesc = $mediaRepo->findGlobalLastMedias();
        $allMediasCount = $mediaRepo->countGlobalMedias();

        return $this->render('media/allMediasGlobal.html.twig', [
            'modoNotifValidationCount' => $modoNotifValidationCount,
            'modoNotifReportCount' => $modoNotifReportCount,
            'userNotifCount' => $userNotifCount,
            'allMediasDesc' => $allMediasDesc,
            'allMediasCount' => $allMediasCount,
        ]);
    }





    // Asynch: Like d'un Media par user (id: idMedia)
    #[Route('/likeMedia/{id}', name: 'app_likeMedia')]
    public function likeMedia(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        if ($this->getUser()) {

            $mediaRepo = $entityManager->getRepository(Media::class);

            $user = $this->getUser();
            $media = $mediaRepo->find($id);

            // Vérification si déjà like = remove (et -1 anotifNbr)
            if ($media->getUserUpvote()->contains($user)) {

                if($media->getUser() != $this->getUser()) {
                    $this->notifController->notifDecrementNbrUpvoteMedia($media->getUser(), $media);
                }

                $media->removeUserUpvote($user);

                $entityManager->flush();

                // Nouveau compte de likes du média
                $likeCount = count($media->getUserUpvote());

                return new JsonResponse(['success' => true, 'newState' => "unliked", 'newCountLikes' => $likeCount]);
            }
            else {
                // Notifs auteur: création notif si premier upvote, sinon incrémentation de la notif (HS car pas d'upvote(ligne de dessous))
                if($media->getUser() != $this->getUser()) {
                    $this->notifController->notifUpvoteMedia($media->getUser(), $media);
                }

                $media->addUserUpvote($user);

                $entityManager->flush();

                // Nouveau compte de likes du média
                $likeCount = count($media->getUserUpvote());

                return new JsonResponse(['success' => true, 'newState' => "liked", 'newCountLikes' => $likeCount]);
            }
        }
        else {
            return new JsonResponse(['success' => false]);
        }
    }




    // Asynch Upvote/unUpvote de mediaPost par user (id: idMediaPost)
    #[Route('/upvoteMediaPost/{id}', name: 'app_upvoteMediaPost')]
    public function upvoteMediaPost(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        if ($this->getUser()) {

            $postLikeRepo = $entityManager->getRepository(MediaPostLike::class);
            $mediaPostRepo = $entityManager->getRepository(MediaPost::class);

            $mediaPost = $mediaPostRepo->find($id);
            $media = $mediaPost->getMedia();

            // Upvote possible que si pas auteur
            if ($this->getUser() != $mediaPost->getUser()) {

                // Si l'utilisateur n'a pas déjà upvoté/downvoté 
                if(count($postLikeRepo->findBy(['user' => $this->getUser(), 'mediaPost' => $mediaPost])) == 0) {

                    $mediaPostLike = new MediaPostLike();

                    $mediaPostLike->setState("upvote");
                    $mediaPostLike->setUser($this->getUser());
                    $mediaPostLike->setMediaPost($mediaPost);

                    // Notifs auteur: création notif si premier upvote, sinon incrémentation de la notif
                    $this->notifController->notifUpvoteMediaPost($mediaPost->getUser(), $mediaPost);

                    $entityManager->persist($mediaPostLike);
                    $entityManager->flush();

                    // recalcul score DownVote/Upvote
                    $newScore = $postLikeRepo->calcMediaPostScore($mediaPost);

                    // JS FLASH: Votre upvote a été pris en compte
                    return new JsonResponse(['success' => true, 'newState' => 'upvoted', 'gameColor' => $media->getGame()->getColor(), 'newScore' => $newScore]);   
                
                } 
                // Si précédent vote était upvote/downvote:
                else {
                    // Si upvote: Retirer l'upvote
                    if($postLikeRepo->findOneBy(['user' => $this->getUser(), 'mediaPost' => $mediaPost])->getState() == "upvote" ) {

                        $mediaPostLike = $postLikeRepo->findOneBy(['user' => $this->getUser(), 'mediaPost' => $mediaPost]);

                        $postLikeRepo->remove($mediaPostLike, true);

                        // Annulation upvote: Décrémente TypeNbr de la notif 
                        $this->notifController->decrAndUpdateNotifMediaPostLike($mediaPost->getUser(), $mediaPost);

                        // recalcul score DownVote/Upvote
                        $newScore = $postLikeRepo->calcMediaPostScore($mediaPost);

                        return new JsonResponse(['success' => true, 'newState' => 'notUpvoted', 'newScore' => $newScore]);   
                    
                    }
                    // Si downvote: Transformer le downvote en upvote 
                    else if($postLikeRepo->findOneBy(['user' => $this->getUser(), 'mediaPost' => $mediaPost])->getState() == "downvote" ) {

                        $mediaPostLike = $postLikeRepo->findOneBy(['user' => $this->getUser(), 'mediaPost' => $mediaPost]);

                        $mediaPostLike->setState("upvote");

                        // 
                        $this->notifController->notifUpvoteMediaPost($mediaPost->getUser(), $mediaPost);

                        $entityManager->persist($mediaPostLike);
                        $entityManager->flush();

                        // recalcul score DownVote/Upvote
                        $newScore = $postLikeRepo->calcMediaPostScore($mediaPost);

                        return new JsonResponse(['success' => true, 'newState' => 'upvoted', 'gameColor' => $media->getGame()->getColor(), 'newScore' => $newScore]);   
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




    // Asynch Downvote/unDownvote de mediaPost par user (id: idMediaPost)
    // (Pas de notif pour downvotes)
    #[Route('/downvoteMediaPost/{id}', name: 'app_downvoteMediaPost')]
    public function downvoteMediaPost(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        if ($this->getUser()) {

            $postLikeRepo = $entityManager->getRepository(MediaPostLike::class);
            $mediaPostRepo = $entityManager->getRepository(MediaPost::class);

            $mediaPost = $mediaPostRepo->find($id);
            $media = $mediaPost->getMedia();

            // Downvote possible que si pas auteur
                if ($this->getUser() != $mediaPost->getUser()) {

                // Si l'utilisateur n'a pas déjà downvoté/upvoté
                if(count($postLikeRepo->findBy(['user' => $this->getUser(), 'mediaPost' => $mediaPost])) == 0) {

                    $mediaPostLike = new MediaPostLike();

                    $mediaPostLike->setState("downvote");
                    $mediaPostLike->setUser($this->getUser());
                    $mediaPostLike->setMediaPost($mediaPost);

                    // Notifs auteur: création notif si premier upvote, sinon incrémentation de la notif
                    // $this->notifController->notifDownvoteMediaPost($mediaPost->getUser(), $mediaPost);

                    $entityManager->persist($mediaPostLike);
                    $entityManager->flush();

                    // recalcul score DownVote/Upvote
                    $newScore = $postLikeRepo->calcMediaPostScore($mediaPost);

                    // JS FLASH: Votre upvote a été pris en compte
                    return new JsonResponse(['success' => true, 'newState' => 'downvoted', 'gameColor' => $media->getGame()->getColor(), 'newScore' => $newScore]);   

                } 
                // Si précédent vote était upvote/downvote:
                else {
                    // Retirer le downvote
                    if($postLikeRepo->findOneBy(['user' => $this->getUser(), 'mediaPost' => $mediaPost])->getState() == "downvote" ) 
                    {
                        $mediaPostLike = $postLikeRepo->findOneBy(['user' => $this->getUser(), 'mediaPost' => $mediaPost]);

                        $postLikeRepo->remove($mediaPostLike, true);

                        // recalcul score DownVote/Upvote
                        $newScore = $postLikeRepo->calcMediaPostScore($mediaPost);

                        return new JsonResponse(['success' => true, 'newState' => 'notDownvoted', 'newScore' => $newScore]);   
                    
                    }
                    // Transformer le downvote en upvote (carrément)
                    else if($postLikeRepo->findOneBy(['user' => $this->getUser(), 'mediaPost' => $mediaPost])->getState() == "upvote" ) 
                    {
                        $mediaPostLike = $postLikeRepo->findOneBy(['user' => $this->getUser(), 'mediaPost' => $mediaPost]);

                        $mediaPostLike->setState("downvote");

                        $entityManager->persist($mediaPostLike);
                        $entityManager->flush();

                        // recalcul score DownVote/Upvote
                        $newScore = $postLikeRepo->calcMediaPostScore($mediaPost);

                        return new JsonResponse(['success' => true, 'newState' => 'downvoted', 'gameColor' => $media->getGame()->getColor(), 'newScore' => $newScore]);   
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




    // Tout les Médias de l'user connecté (from profil)
    #[Route('/allMediasUser', name: 'app_allMediasUser')]
    public function getAllMediasUser(EntityManagerInterface $entityManager): Response
    {
        $mediaRepo = $entityManager->getRepository(Media::class);
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

        $userMediasDesc = $mediaRepo->findBy(['user' => $this->getUser()], ['publish_date' => 'DESC']);
        $userMediasCount = count($userMediasDesc);

        return $this->render('user/allMediasUser.html.twig', [
            'modoNotifValidationCount' => $modoNotifValidationCount,
            'modoNotifReportCount' => $modoNotifReportCount,
            'userNotifCount' => $userNotifCount,
            'userMedias' => $userMediasDesc,
            'userMediasCount' => $userMediasCount,
        ]);

    }




    // Fermeture de Média par author (slug: slugMédia)  
    #[Route('/closeMedia/{slug}', name: 'app_closeMedia')]
    public function closeMedia(EntityManagerInterface $entityManager, string $slug, Request $request): Response
    {
        $mediaRepo = $entityManager->getRepository(Media::class);

        $media = $mediaRepo->findOneBy(['slug' => $slug]);

        // Vérif si user est bien l'auteur du média
        if ($this->getUser() == $media->getUser()) {

            $media->setStatus("closed");
            $entityManager->flush();

            $this->addFlash('success', 'Le média a bien été fermé');
            return $this->redirectToRoute('app_mediaDetail', ['slug' => $slug]); 
        }
        else {
            $this->addFlash('error', 'Vous devez être l\'auteur du média ou admin pour pouvoir le fermer');
            return $this->redirectToRoute('app_mediaDetail', ['slug' => $slug]); 
        }
    }
    


    // Réouverture du Média par author (slug: slugMédia)  
    #[Route('/openMedia/{slug}', name: 'app_openMedia')]
    public function openMedia(EntityManagerInterface $entityManager, string $slug, Request $request): Response
    {
        $mediaRepo = $entityManager->getRepository(Media::class);

        $media = $mediaRepo->findOneBy(['slug' => $slug]);

        // Vérif si user est bien l'auteur du media
        if ($this->getUser() == $media->getUser()) {

            $media->setStatus("open");
            $entityManager->flush();

            $this->addFlash('success', 'Le media a bien été rouvert');
            return $this->redirectToRoute('app_mediaDetail', ['slug' => $slug]); 
        }
        else {
            $this->addFlash('error', 'Vous devez être l\'auteur du media ou admin pour pouvoir le rouvrir');
            return $this->redirectToRoute('app_mediaDetail', ['slug' => $slug]); 
        }
    }
    

}
