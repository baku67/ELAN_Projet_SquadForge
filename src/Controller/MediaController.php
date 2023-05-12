<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\MediaPost;
use App\Entity\MediaPostLike;
use App\Entity\Game;
use App\Form\MediaType;
use App\Form\MediaPostType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MediaController extends AbstractController
{

    // Listing tous les Médias du jeu 
    #[Route('/allMedias/{gameIdFrom}', name: 'app_allMedias')]
    public function getGameMedias(EntityManagerInterface $entityManager, int $gameIdFrom, Request $request): Response
    {

        $gameRepo = $entityManager->getRepository(Game::class);
        $gameFrom = $gameRepo->find($gameIdFrom);

        $mediaRepo = $entityManager->getRepository(Media::class);

        // Requete (juste listé par publish_date pour l'instant)
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('m')
            ->from('App\Entity\Media', 'm')
            ->where('m.game = :game')
            ->setParameter('game', $gameFrom)
            // ->orderBy('t.topicPostsCount', 'DESC') champ non mappé: faut ajouter un select avec CASE WHEN etc ...
            ->orderBy('m.publish_date', 'DESC');
        $gameMediasDesc = $queryBuilder->getQuery()->getResult();

        $gameMediasCount = count($gameMediasDesc);





        // Form ajout Media (Affichage et handleRequest)
        $media = new Media();
        $form2 = $this->createForm(MediaType::class, $media);
        $form2 -> handleRequest($request);

        // Vérifs/Filtres
        if($form2->isSubmitted()) {

            if($this->getUser()) {

                if($form2->isValid()) {

                    // Hydrataion du "Media" a partir des données du form
                    $media = $form2->getData();

                    // Init de la publish_date du comment
                    $media->setPublishDate(new \DateTime());
                    $media->setGame($gameFrom);
                    $media->setUser($this->getUser());
                    $media->setStatus("ouvert");
                    // En attendant le système de validation avant publication par un modo:
                    $media->setValidated("validated");
                    
                    // Récupération du titre
                    $titleInputValue = $form2->get('title')->getData();

                    // Récupération de l'image du média
                    $mediaImg = $form2->get('url')->getData();

                    // Vérification de l'extension (.png, .jpg, .jpeg, .gif)
                    $fileExt = $mediaImg->getClientOriginalExtension();
                    if ($fileExt != "png" && $fileExt != "jpg" && $fileExt != "jpeg" && $fileExt != "gif") {
                        $this->addFlash('error', 'Le format "' . $fileExt . '" n\'est pas supporté');
                        return $this->redirectToRoute('app_game', ['id' => $gameFrom->getId()]);
                    }

                    // Vérification de la taille du fichier + Vérif que c'est bien un fichier qui est uploadé (pour pouvoir utiliser getSize())
                    // Attention: vérifications Front en amont "maxFileSize" dans "gameDetails.html.twig"
                    $maxFileSize = 10 * 1024 * 1024; /* (10MB) */
                    if ($mediaImg instanceof UploadedFile && $mediaImg->getSize() > $maxFileSize) {
                        $this->addFlash('error', 'Le fichier est trop volumineux');
                        return $this->redirectToRoute('app_game', ['id' => $gameFrom->getId()]);
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
                        return $this->redirectToRoute('app_game', ['id' => $gameFrom->getId()]);
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
                        return $this->redirectToRoute('app_game', ['id' => $gameFrom->getId()]);

                    } else {
                        
                        $this->addFlash('error', 'Le titre doit faire au minimum 5 mots !');
                        return $this->redirectToRoute('app_game', ['id' => $gameFrom->getId()]);
                    }

                } 
                else {
                    $this->addFlash('error', 'Les données envoyées ne sont pas valides');
                    return $this->redirectToRoute('app_game', ['id' => $gameFrom->getId()]);
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









    // Médias Details (id: idMedia)
    #[Route('/mediaDetail/{id}', name: 'app_mediaDetail')]
    public function getMediaDetail(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {

        $mediaRepo = $entityManager->getRepository(Media::class);
        $mediaPostRepo = $entityManager->getRepository(MediaPost::class);

        $media = $mediaRepo->find($id);

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

                // Vérification si le media est ouvert
                if ($media->getStatus() == "open") {
                    
                    if($form->isValid()) {

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
                            return $this->redirectToRoute('app_mediaDetail', ['id' => $media->getId()]);
                        // } else {
                            
                        //     $this->addFlash('error', 'Le titre doit faire au minimum 5 mots !');
                        //     return $this->redirectToRoute('app_game', ['id' => $game->getId()]);
                        // }
    
                    } 
                    else {
                        $this->addFlash('error', 'Les données envoyées ne sont pas valides');
                        return $this->redirectToRoute('app_mediaDetail', ['id' => $media->getId()]);
                    }   

                }
                else {
                    $this->addFlash('error', 'Le media a été fermé, vous ne pouvez plus le commenter.');
                    return $this->redirectToRoute('app_mediaDetail', ['id' => $media->getId()]);
                }


                
            }
            else {
                $this->addFlash('error', 'Vous devez être connecté pour publier un post');
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('media/mediaDetail.html.twig', [
            'formAddMediaPost' => $form->createView(),
            'media' => $media,
            'game' => $mediaGame,
            'mediaPosts' => $mediaPosts
        ]);

    }











    // Tout les Medias globaux (from /homePage)
    #[Route('/allMediasGlobal', name: 'app_allMediasGlobal')]
    public function getAllMediasGlobal(EntityManagerInterface $entityManager): Response
    {

        $gameRepo = $entityManager->getRepository(Game::class);
        $mediaRepo = $entityManager->getRepository(Media::class);

        // Liste de tous les Médias 
        // Todo: findBy plutot
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('m')
            ->from('App\Entity\Media', 'm')
            ->orderBy('m.publish_date', 'DESC');
        $allMediasDesc = $queryBuilder->getQuery()->getResult();

        $allMediasCount = count($allMediasDesc);


        return $this->render('media/allMediasGlobal.html.twig', [
            'allMediasDesc' => $allMediasDesc,
            'allMediasCount' => $allMediasCount,
        ]);
    }







    // Like d'un Media par user (id: idMedia)
    #[Route('/likeMedia/{id}', name: 'app_likeMedia')]
    public function likeMedia(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {

        if ($this->getUser()) {

            $mediaRepo = $entityManager->getRepository(Media::class);

            $user = $this->getUser();
            $media = $mediaRepo->find($id);

            // Vérification si déjà like = remove
            if ($media->getUserUpvote()->contains($user)) {

                // En attendant l'asynch (pour l'instant le redirect est deg)
                $media->removeUserUpvote($user);
                // $this->addFlash('success', 'Votre avez unliké');

                $entityManager->flush();

                // Nouveau compte de likes du média
                $likeCount = count($media->getUserUpvote());

                // unliké
                return new JsonResponse(['success' => true, 'newState' => "unliked", 'newCountLikes' => $likeCount]);

            }
            else {
                // En attendant l'asynch (pour l'instant le redirect est deg)
                $media->addUserUpvote($user);
                // $this->addFlash('success', 'Votre avez liké');

                $entityManager->flush();

                // Nouveau compte de likes du média
                $likeCount = count($media->getUserUpvote());

                // liké
                return new JsonResponse(['success' => true, 'newState' => "liked", 'newCountLikes' => $likeCount]);
            }

            // $entityManager->flush();

            // return $this->redirectToRoute('app_mediaDetail', ['id' => $media->getId()]);

        }
        else {

            return new JsonResponse(['success' => false]);

        }
        
    }











    // Upvote/unUpvote de mediaPost par user (id: idMediaPost)
    #[Route('/upvoteMediaPost/{id}', name: 'app_upvoteMediaPost')]
    public function upvoteMediaPost(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {

        if ($this->getUser()) {

            $mediaPostRepo = $entityManager->getRepository(MediaPost::class);
            $postLikeRepo = $entityManager->getRepository(MediaPostLike::class);
            $mediaPost = $mediaPostRepo->find($id);


            // Si l'utilisateur n'a pas déjà upvoté 
            if(count($postLikeRepo->findBy(['user' => $this->getUser(), 'mediaPost' => $mediaPost])) == 0) {


                $mediaPostLike = new MediaPostLike();
                $mediaPostLike->setState("upvote");
                $mediaPostLike->setUser($this->getUser());
                $mediaPostLike->setMediaPost($mediaPost);

                $entityManager->persist($mediaPostLike);
                $entityManager->flush();

                // $mediaPost->addMediaPostLike($mediaPostLike);

                $this->addFlash('success', 'Votre upvote a été pris en compte');
                return $this->redirectToRoute('app_mediaDetail', ['id' => $mediaPost->getMedia()->getId()]); 
            
            } 
            else {

                if($postLikeRepo->findOneBy(['user' => $this->getUser(), 'mediaPost' => $mediaPost])->getState() == "upvote" ) {

                    $mediaPostLike = $postLikeRepo->findOneBy(['user' => $this->getUser(), 'mediaPost' => $mediaPost]);

                    $postLikeRepo->remove($mediaPostLike, true);
                    // $topicPostRepo->flush();

                    $this->addFlash('success', 'Votre upvote a été retiré');
                    return $this->redirectToRoute('app_mediaDetail', ['id' => $mediaPost->getMedia()->getId()]); 
                
                }
                else if($postLikeRepo->findOneBy(['user' => $this->getUser(), 'mediaPost' => $mediaPost])->getState() == "downvote" ) {

                    $mediaPostLike = $postLikeRepo->findOneBy(['user' => $this->getUser(), 'mediaPost' => $mediaPost]);

                    $mediaPostLike->setState("upvote");

                    $entityManager->persist($mediaPostLike);
                    $entityManager->flush();

                    $this->addFlash('success', 'Votre upvote a été pris en compte');
                    return $this->redirectToRoute('app_mediaDetail', ['id' => $mediaPost->getMedia()->getId()]); 
                }
            }

        }
        else {
            $this->addFlash('error', 'Vous devez être connecté pour upvoter un post');
            return $this->redirectToRoute('app_login');
        }
    
    }




    // Downvote/unDownvote de mediaPost par user (id: idMediaPost)
    #[Route('/downvoteMediaPost/{id}', name: 'app_downvoteMediaPost')]
    public function downvoteMediaPost(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {

        if ($this->getUser()) {

            $mediaPostRepo = $entityManager->getRepository(MediaPost::class);
            $postLikeRepo = $entityManager->getRepository(MediaPostLike::class);
            $mediaPost = $mediaPostRepo->find($id);


            // Si l'utilisateur n'a pas déjà downvoté 
            if(count($postLikeRepo->findBy(['user' => $this->getUser(), 'mediaPost' => $mediaPost])) == 0) {


                $mediaPostLike = new MediaPostLike();
                $mediaPostLike->setState("downvote");
                $mediaPostLike->setUser($this->getUser());
                $mediaPostLike->setMediaPost($mediaPost);

                $entityManager->persist($mediaPostLike);
                $entityManager->flush();

                // $mediaPost->addMediaPostLike($mediaPostLike);

                $this->addFlash('success', 'Votre downvote a été pris en compte');
                return $this->redirectToRoute('app_mediaDetail', ['id' => $mediaPost->getMedia()->getId()]); 
            
            } 
            else {

                if($postLikeRepo->findOneBy(['user' => $this->getUser(), 'mediaPost' => $mediaPost])->getState() == "downvote" ) {

                    $mediaPostLike = $postLikeRepo->findOneBy(['user' => $this->getUser(), 'mediaPost' => $mediaPost]);

                    $postLikeRepo->remove($mediaPostLike, true);
                    // $topicPostRepo->flush();

                    $this->addFlash('success', 'Votre downvote a été retiré');
                    return $this->redirectToRoute('app_mediaDetail', ['id' => $mediaPost->getMedia()->getId()]); 
                
                }
                else if($postLikeRepo->findOneBy(['user' => $this->getUser(), 'mediaPost' => $mediaPost])->getState() == "upvote" ) {

                    $mediaPostLike = $postLikeRepo->findOneBy(['user' => $this->getUser(), 'mediaPost' => $mediaPost]);

                    $mediaPostLike->setState("downvote");

                    $entityManager->persist($mediaPostLike);
                    $entityManager->flush();

                    $this->addFlash('success', 'Votre downvote a été pris en compte');
                    return $this->redirectToRoute('app_mediaDetail', ['id' => $mediaPost->getMedia()->getId()]); 
                }
            }

        }
        else {
            $this->addFlash('error', 'Vous devez être connecté pour upvoter un post');
            return $this->redirectToRoute('app_login');
        }
    
    }








    // Tout les Médias de l'user connecté (from profil)
    #[Route('/allMediasUser', name: 'app_allMediasUser')]
    public function getAllMediasUser(EntityManagerInterface $entityManager): Response
    {
        $mediaRepo = $entityManager->getRepository(Media::class);

        $userMediasDesc = $mediaRepo->findBy(['user' => $this->getUser()], ['publish_date' => 'DESC']);
        $userMediasCount = count($userMediasDesc);

        return $this->render('user/allMediasUser.html.twig', [
            'userMedias' => $userMediasDesc,
            'userMediasCount' => $userMediasCount,
        ]);

    }











        // Fermeture de Média par author (id: idMédia)  
        #[Route('/closeMedia/{id}', name: 'app_closeMedia')]
        public function closeMedia(EntityManagerInterface $entityManager, int $id, Request $request): Response
        {
            $mediaRepo = $entityManager->getRepository(Media::class);
    
            $media = $mediaRepo->find($id);
    
            // Vérif si user est bien l'auteur du média
            if ($this->getUser() == $media->getUser()) {
    
                $media->setStatus("closed");
                $entityManager->flush();
    
                $this->addFlash('success', 'Le média a bien été fermé');
                return $this->redirectToRoute('app_mediaDetail', ['id' => $id]); 
            }
            else {
                $this->addFlash('error', 'Vous devez être l\'auteur du média ou admin pour pouvoir le fermer');
                return $this->redirectToRoute('app_mediaDetail', ['id' => $id]); 
            }
        }
    
        // Réouverture du Média par author (id: idMedia)  
        #[Route('/openMedia/{id}', name: 'app_openMedia')]
        public function openMedia(EntityManagerInterface $entityManager, int $id, Request $request): Response
        {
            $mediaRepo = $entityManager->getRepository(Media::class);
    
            $media = $mediaRepo->find($id);
    
            // Vérif si user est bien l'auteur du media
            if ($this->getUser() == $media->getUser()) {
    
                $media->setStatus("open");
                $entityManager->flush();
    
                $this->addFlash('success', 'Le media a bien été rouvert');
                return $this->redirectToRoute('app_mediaDetail', ['id' => $id]); 
            }
            else {
                $this->addFlash('error', 'Vous devez être l\'auteur du media ou admin pour pouvoir le rouvrir');
                return $this->redirectToRoute('app_mediaDetail', ['id' => $id]); 
            }
        }
    

}
