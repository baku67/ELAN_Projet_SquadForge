<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\Censure;
use App\Entity\Topic;
use App\Entity\Media;
use App\Form\CensureType;
use App\Entity\Report;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use DateTime;
use DateTimeInterface;


class ModerationController extends AbstractController
{

    private $notifController;

    public function __construct(NotificationController $notifController) {

        $this->notifController = $notifController;
    }


    #[Route('/moderationDashboard', name: 'app_moderationDashboard')]
    public function moderationDashboard(EntityManagerInterface $entityManager, Request $request): Response
    {
        if ( $this->getUser() && in_array('ROLE_MODO', $this->getUser()->getRoles()) ) {

            $topicRepo = $entityManager->getRepository(Topic::class);
            $mediaRepo = $entityManager->getRepository(Media::class);
            $notifRepo = $entityManager->getRepository(Notification::class);
            $reportRepo = $entityManager->getRepository(Report::class);

            // Onglet notifs Bulle nbr "non-vues" (int si connécté, null sinon)
            $userNotifCount = $this->getUser() ? count($notifRepo->findByUserNotSeen($this->getUser())) : null;
            // Si userModo: Bulles nbr éléments en attente de validation (int si modo, null sinon)
            if($this->getUser() && in_array('ROLE_MODO', $this->getUser()->getRoles())) {
                // On compte les Topic et Médias status "waiting"
                $mediasWaitings = count($mediaRepo->findBy(["validated" => "waiting"]));
                $topicsWaitings = count($topicRepo->findBy(["validated" => "waiting"]));
                $modoNotifCount = $mediasWaitings + $topicsWaitings;
            }
            else {
                $modoNotifCount = null;
            }

            // Récup des reports grouped By object
            // $reports = $reportRepo->findBy([], ['creation_date' => 'DESC']);
            $reports = $reportRepo->getAllReportsGroupedByOjectIdAndType();

            $censureRepo = $entityManager->getRepository(Censure::class);
            $censureWords = $censureRepo->findBy([], ["creation_date" => "DESC"]);

            // Récup mini liste des topics/médias en attente (tout l'historique dans le détail "voir tout", avec filtre "en attente" only)
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
            return $this->render('moderation/modoDashboard.html.twig', [
                'reports' => $reports,
                'modoNotifCount' => $modoNotifCount,
                'userNotifCount' => $userNotifCount,
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




    // Ajax: affichage du détails du report (aperçu message et/ou image signalé)
    #[Route('/reportDetails/{objectType}/{objectId}', name: 'app_reportDetails')]
    public function reportDetails(EntityManagerInterface $entityManager, string $objectType, int $objectId, Request $request): Response
    {

        switch ($objectType) {

            case 'media':
                
                $mediaRepo = $entityManager->getRepository(Media::class);
                $mediaReported = $mediaRepo->find($objectId);

                $objectDetails = [$mediaReported->getTitle(), $mediaReported->getPublishDate(), $mediaReported->getGame()->getTitle(), $mediaReported->getUser()->getPseudo(), $mediaReported->getUrl()];
            
                break;


            case 'topic':

                $topicRepo = $entityManager->getRepository(Topic::class);
                $topicReported = $topicRepo->find($objectId);

                $objectDetails = [$topicReported->getTitle(), $topicReported->getPublishDate(), $topicReported->getGame()->getTitle(), $topicReported->getUser()->getPseudo()];

                break;


            //  case juste post ?

            
            default:
                break;
        }

        
        return new JsonResponse(['success' => true, 'objectType' => $objectType, 'object' => $objectDetails]); 
    }


    // ReportCard: "Innocenter" Suppression des reports d'un objet
    #[Route('/removeReports/{objectType}/{objectId}', name: 'app_removeReports')]
    public function removeReports(EntityManagerInterface $entityManager, string $objectType, int $objectId, Request $request): Response
    {

        if( $this->getUser() && in_array('ROLE_MODO', $this->getUser()->getRoles()) ) {

            $reportRepo = $entityManager->getRepository(Report::class);
            $objectReports = $reportRepo->findBy(["objectType" => $objectType, "objectId" => $objectId]);

            foreach ($objectReports as $report) {
                $reportRepo->remove($report, true);
            }

            $this->addFlash('success', 'Innocenté');
            return $this->redirectToRoute('app_moderationDashboard');

        }
        else {
            $this->addFlash('error', 'Vous devez être modérateur pour innocenter un report');
            return $this->redirectToRoute('app_login');
        }
    }


   

    // ReportCard: Censure d'un objet depuis Signalements (+ Ban/Mute auteur) (+ Notifs Author et Reporters)
    #[Route('/censureObject/{objectType}/{objectId}', name: 'app_censureObject')]
    public function censureObject(EntityManagerInterface $entityManager, string $objectType, int $objectId, Request $request): Response
    {

        if( $this->getUser() && in_array('ROLE_MODO', $this->getUser()->getRoles()) ) {

            // Récupération des reports en relation avec l'objet (pour envoyer notif "merci")
            $reportRepo = $entityManager->getRepository(Report::class);
            $objectReports = $reportRepo->findBy(["objectType" => $objectType, "objectId" => $objectId]);

            // Censure de l'objet (Si Topic/Media: suppression + Children, Si postTopic/postMedia: setText('le comm a été suppr'))
            switch ($objectType) {

                case 'media':
                    
                    $mediaRepo = $entityManager->getRepository(Media::class);
                    $mediaReported = $mediaRepo->find($objectId);
                    $notifPreview = $mediaReported->getTile();
                    $author = $mediaReported->getUser();
    
                    $mediaRepo->remove($mediaReported, true);

                    // Suppr des reports associés
                    $reportRepo = $entityManager->getRepository(report::class);
                    $reports = $reportRepo->findBy(["objectType" => $objectType, "objectId" => $objectId]);
                    foreach ($reports as $report) {
                        $reportRepo->remove($report, true);
                    }
                
                    break;
    
    
                case 'topic':
    
                    $topicRepo = $entityManager->getRepository(Topic::class);
                    $topicReported = $topicRepo->find($objectId);
                    $notifPreview = $topicReported->getTile();
                    $author = $topicReported->getUser();
    
                    $topicRepo->remove($topicReported, true);

                    // Suppr des reports associés
                    $reportRepo = $entityManager->getRepository(report::class);
                    $reports = $reportRepo->findBy(["objectType" => $objectType, "objectId" => $objectId]);
                    foreach ($reports as $report) {
                        $reportRepo->remove($report, true);
                    }


                    break;
    
    
                // Case juste post ?
                // Remplacement par text "le comm a été suppr
                
                default:
                    break;
            }

            

            if ($request->request->get('mode') == 'void') {
                // Pas de changement de status Author, mais notif censure publication
                $this->notifController->notifCensureAuthor($author, $objectType, $notifPreview);
            }
            else if ($request->request->get('mode') == 'mute') {
                // Maj Status et endDateStatus Author
                $author->setStatus("muted");
                $dateString = $request->request->get('endDate');
                $date = DateTime::createFromFormat('Y-m-d', $dateString);
                $author->setEndDateStatus($date);

                // Envoi notif à l'Author (censure + Ban/Mute) et notifs "merci" aux reporters
                $this->notifController->notifBanMuteAuthor("mute", $author, $date);
                foreach ($$objectReports as $report) {
                    $this->notifController->notifThxReporter($report->getUserReporter());
                }

                $entityManager->persist($author);
            }
            else if ($request->request->get('mode') == 'ban') {
                // Maj Status et endDateStatus Author
                $author->setStatus("banned");
                $dateString = $request->request->get('endDate');
                $date = DateTime::createFromFormat('Y-m-d', $dateString);
                $author->setEndDateStatus($date);

                // Envoi notif à l'Author (censure + Ban/Mute) et notifs "merci" aux reporters
                $this->notifController->notifBanMuteAuthor("ban", $author, $date);
                foreach ($$objectReports as $report) {
                    $this->notifController->notifThxReporter($report->getUserReporter());
                }

                $entityManager->persist($author);
            }

            $entityManager->flush();

            $this->addFlash('success', 'L\'élément a été censuré');
            return $this->redirectToRoute('app_moderationDashboard');
        }
        else {
            $this->addFlash('error', 'Vous devez être modérateur pour censurer un élément');
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
            
            // Date publish mise à jour à la validation
            $media->setPublishDate(new \DateTime());
            $media->setValidated("validated");

            $entityManager->persist($media);
            $entityManager->flush();

            // Notification à l'auteur
            $this->notifController->notifValidatedMedia($media->getUser(), $media);

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

            // Notification à l'auteur
            $this->notifController->notifRefusedMedia($media->getUser(), $media);

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





        // id: Topic à valider
        #[Route('/validateTopic/{id}', name: 'app_validateTopic')]
        public function validateTopic(EntityManagerInterface $entityManager, int $id, Request $request): Response
        {
            if ( $this->getUser() && in_array('ROLE_MODO', $this->getUser()->getRoles()) ) {
    
                $topicRepo = $entityManager->getRepository(Topic::class);
                $topic = $topicRepo->find($id);
                
                // Date publish mise à jour à la validation
                $topic->setPublishDate(new \DateTime());
                $topic->setValidated("validated");

                // Notification à l'auteur
                $this->notifController->notifValidatedTopic($topic->getUser(), $topic);

                $entityManager->persist($topic);
                $entityManager->flush();
    
                $this->addFlash('success', 'Le topic a été validé');
                return $this->redirectToRoute('app_moderationDashboard');

            }
            else {
                $this->addFlash('error', 'Vous devez être modérateur pour valider un topic');
                return $this->redirectToRoute('app_login');
            }
        }
    
    
    
        // id: Topic à refuser
        #[Route('/refuseTopic/{id}', name: 'app_refuseTopic')]
        public function refuseTopic(EntityManagerInterface $entityManager, int $id, Request $request): Response
        {
            if ( $this->getUser() && in_array('ROLE_MODO', $this->getUser()->getRoles()) ) {
    
                $topicRepo = $entityManager->getRepository(Topic::class);
                $topic = $topicRepo->find($id);
                
                $topic->setValidated("refused");

                // Notification à l'auteur
                $this->notifController->notifRefusedTopic($topic->getUser(), $topic);

                $entityManager->persist($topic);
                $entityManager->flush();
    
                $this->addFlash('success', 'Le topic a été refusé');
                return $this->redirectToRoute('app_moderationDashboard');
    
            }
            else {
                $this->addFlash('error', 'Vous devez être modérateur pour refuser un topic');
                return $this->redirectToRoute('app_login');
            }
        }
    
}
