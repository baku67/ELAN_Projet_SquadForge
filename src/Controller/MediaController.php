<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Game;
use App\Form\MediaType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

}
