<?php

namespace App\Controller;

use App\Entity\Censure;
use App\Entity\Topic;
use App\Entity\Media;
use App\Form\CensureType;
// use App\Entity\MediaPost;
// use App\Entity\TopicPost;
// use App\Entity\Game;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class ModerationController extends AbstractController
{
    #[Route('/moderationDashboard', name: 'app_moderationDashboard')]
    public function moderationDashboard(EntityManagerInterface $entityManager, Request $request): Response
    {

        if ( $this->getUser() && in_array('ROLE_MODO', $this->getUser()->getRoles()) ) {

            $censureRepo = $entityManager->getRepository(Censure::class);
            $censureWords = $censureRepo->findBy([], ["creation_date" => "DESC"]);

            // Récup mini liste des topics/médias en attente (tout l'historique dans le détail "voir tout", avec filtre "en attente" only)
            $topicRepo = $entityManager->getRepository(Topic::class);
            $mediaRepo = $entityManager->getRepository(Media::class);
            $lastWaitingTopics = $topicRepo->findLastWaitingTopics();
            $lastWaitingMedias = $mediaRepo->findLastWaitingMedias();


            $mediaRepo = $entityManager->getRepository(Media::class);
    
            // Ajouter le formType d'ajout
            $censure = new Censure;
            $form = $this->createForm(CensureType::class, $censure);
            $form -> handleRequest($request);

            if($form->isSubmitted()) {

                if($form->isValid()) {

                    $censure = $form->getData();

                    // mot toLower pour faciliter validation
                    $censureWord = $form->get('word')->getData();
                    $censure->setWord(strtolower($censureWord));
    
                    // Init de la publish_date du comment
                    $censure->setCreationDate(new \DateTime());
                    $censure->setUser($this->getUser());

                    
                    // Check doublons
                    if ($censureRepo->findOneBy(['word' => $censureWord])) {
                        
                        $this->addFlash('error', 'Le mot a déjà été ajouté');
                        return $this->redirectToRoute('app_moderationDashboard');
                    
                    }
                    else {
                        // Maj BDD
                        $entityManager->persist($censure);
                        $entityManager->flush();

                        $this->addFlash('success', 'Le mot a été ajouté');
                        return $this->redirectToRoute('app_moderationDashboard');

                    }

                }

            }


            return $this->render('moderation/index.html.twig', [
                'formAddCensoredWord' => $form->createView(),
                'censureWords' => $censureWords,
                'lastWaitingTopics' => $lastWaitingTopics,
                'lastWaitingMedias' => $lastWaitingMedias,
            ]);
    
        }
        else {

            $this->addFlash('error', 'Vous devez être modérateur pour accéder à cette page');
            return $this->redirectToRoute('app_login');

        }

        

    }


    // id: idCensure
    #[Route('/deleteCensure/{id}', name: 'app_deleteCensure')]
    public function deleteCensure(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {

        if ( $this->getUser() && in_array('ROLE_MODO', $this->getUser()->getRoles()) ) {

            $censureRepo = $entityManager->getRepository(Censure::class);
            $censure = $censureRepo->find($id);

            $censureRepo->remove($censure, true);

            $this->addFlash('success', 'Le mot a été retiré');
            return $this->redirectToRoute('app_moderationDashboard');

        }
        else {

            $this->addFlash('error', 'Vous devez être modérateur pour retirer un mot');
            return $this->redirectToRoute('app_login');

        }

    }


    // id: Media à valider
    #[Route('/validateMedia/{id}', name: 'app_validateMedia')]
    public function validateMedia(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {

        if ( $this->getUser() && in_array('ROLE_MODO', $this->getUser()->getRoles()) ) {

            $mediaRepo = $entityManager->getRepository(Media::class);
            $media = $mediaRepo->find($id);
            
            $media->setValidated("validated");
            $entityManager->persist($media);
            $entityManager->flush();

            $this->addFlash('success', 'Le média a été validé');
            return $this->redirectToRoute('app_moderationDashboard');

        }
        else {

            $this->addFlash('error', 'Vous devez être modérateur pour valider un média');
            return $this->redirectToRoute('app_login');

        }
    }



    // id: Media à refuser
    #[Route('/refuseMedia/{id}', name: 'app_refuseMedia')]
    public function refuseMedia(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {

        if ( $this->getUser() && in_array('ROLE_MODO', $this->getUser()->getRoles()) ) {

            $mediaRepo = $entityManager->getRepository(Media::class);
            $media = $mediaRepo->find($id);
            
            $media->setValidated("refused");
            $entityManager->persist($media);
            $entityManager->flush();

            $this->addFlash('success', 'Le média a été refusé');
            return $this->redirectToRoute('app_moderationDashboard');

        }
        else {

            $this->addFlash('error', 'Vous devez être modérateur pour refuser un média');
            return $this->redirectToRoute('app_login');

        }
    }
}
