<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\Censure;
use App\Entity\Topic;
use App\Entity\TopicPost;
use App\Entity\Media;
use App\Entity\MediaPost;
use App\Form\CensureType;
use App\Entity\Report;
use App\Entity\User;

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
                $modoNotifValidationCount = $mediasWaitings + $topicsWaitings;
                // Nombre de cards report (groupée, != nbr reports)
                $modoNotifReportCount = count($reportRepo->getAllReportsGroupedByOjectIdAndType());
            }
            else {
                $modoNotifValidationCount = null;
                $modoNotifReportCount = null;
            }

            // Récup des reports grouped By object
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
                'modoNotifValidationCount' => $modoNotifValidationCount,
                'modoNotifReportCount' => $modoNotifReportCount,
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

        $reportRepo = $entityManager->getRepository(Report::class);

        switch ($objectType) {

            case 'media':
                
                $mediaRepo = $entityManager->getRepository(Media::class);
                $mediaReported = $mediaRepo->find($objectId);
                $author = $mediaReported->getUser();

                $objectDetails = [
                    "title" => $mediaReported->getTitle(), 
                    "date" => $mediaReported->getPublishDate(), 
                    "game" => $mediaReported->getGame()->getTitle(), 
                    "author" => $mediaReported->getUser()->getPseudo(), 
                    "img" => $mediaReported->getUrl(),
                    "authorNbrCensures" => $mediaReported->getUser()->getNbrCensures(),
                ];
                // Nbr de reports par motif de signalement:
                $nbrReportsPerMotif = $reportRepo->getNbrReportsPerMotif($objectType, $objectId);
                    
                break;


            case 'topic':

                $topicRepo = $entityManager->getRepository(Topic::class);
                $topicReported = $topicRepo->find($objectId);
                $author = $topicReported->getUser();

                $objectDetails = [
                    "title" => $topicReported->getTitle(), 
                    "date" => $topicReported->getPublishDate(), 
                    "game" => $topicReported->getGame()->getTitle(), 
                    "author" => $topicReported->getUser()->getPseudo(),
                    "authorNbrCensures" => $topicReported->getUser()->getNbrCensures(),
                ];
                // Nbr de reports par motif de signalement:
                $nbrReportsPerMotif = $reportRepo->getNbrReportsPerMotif($objectType, $objectId);
                    
                break;

            
            case 'topicPost':

                $topicPostRepo = $entityManager->getRepository(TopicPost::class);
                $topicPostReported = $topicPostRepo->find($objectId);
                $author = $topicPostReported->getUser();

                $objectDetails = [
                    "title" => $topicPostReported->getText(), 
                    "date" => $topicPostReported->getPublishDate(), 
                    "game" => $topicPostReported->getTopic()->getGame()->getTitle(), 
                    "author" => $topicPostReported->getUser()->getPseudo(),
                    "authorNbrCensures" => $topicPostReported->getUser()->getNbrCensures(),
                ];
                // Nbr de reports par motif de signalement:
                $nbrReportsPerMotif = $reportRepo->getNbrReportsPerMotif($objectType, $objectId);

                break;

            case 'mediaPost':

                $mediaPostRepo = $entityManager->getRepository(MediaPost::class);
                $mediaPostReported = $mediaPostRepo->find($objectId);
                $author = $mediaPostReported->getUser();

                $objectDetails = [
                    "title" => $mediaPostReported->getText(), 
                    "date" => $mediaPostReported->getPublishDate(), 
                    "game" => $mediaPostReported->getMedia()->getGame()->getTitle(), 
                    "author" => $mediaPostReported->getUser()->getPseudo(),
                    "authorNbrCensures" => $mediaPostReported->getUser()->getNbrCensures(),
                ];
                // Nbr de reports par motif de signalement:
                $nbrReportsPerMotif = $reportRepo->getNbrReportsPerMotif($objectType, $objectId);

                break;

            
            default:
                break;
        }


        // Statut actuel de l'auteur (et date de fin si pénalisé)
        $authorStatus = null;
        $authorDateEnd = null;

        if ($author->isMuted()) {
            $authorStatus = "muted";
            $authorDateEnd = $author->getEndDateStatus();
        } else if ($author->isBanned()) {
            $authorStatus = "banned";
            $authorDateEnd = $author->getEndDateStatus();
        }

        
        return new JsonResponse(['success' => true, 'objectType' => $objectType, 'object' => $objectDetails, 'nbrReportsPerMotifArray' => $nbrReportsPerMotif, 'authorStatus' => $authorStatus, 'authorDateEndStatus' => $authorDateEnd]); 
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

        // Check si la sentence est supérieur à celle deja en place pour l'auteur (si c'est le cas, sinon applique)
        function applySentence(Request $request, User $author, ModerationController $controller, NotificationController $notifController, string $objectType, string $objectText, $objectReports) {

            if ($request->request->get('mode') == 'void') {
                // Pas de changement de status Author, mais notif censure publication
                $notifController->notifCensureAuthor($author, $objectType, $objectText);

                return true;
            }
            else if ($request->request->get('mode') == 'mute') {

                // Ok que si (aucun status) ou (si muted et date de fin inférieur a nouvelle date)
                if( ($author->getStatus() == 'muted') && (DateTime::createFromFormat('Y-m-d', $request->request->get('endDate')) > $author->getEndDateStatus()) || $author->getStatus() == "" ) {

                    // Maj Status et endDateStatus Author
                    $author->setStatus("muted");
                    $dateString = $request->request->get('endDate');
                    $date = DateTime::createFromFormat('Y-m-d', $dateString);
                    $author->setEndDateStatus($date);

                    // Envoi notif à l'Author (censure + Ban/Mute) et notifs "merci" aux reporters
                    $notifController->notifCensureAuthor($author, $objectType, $objectText);
                    $notifController->notifBanMuteAuthor("mute", $author, $date);
                    foreach ($objectReports as $report) {
                        $notifController->notifThxReporters($report->getUserReporter());
                    }

                    return true;
                }
                else if ($author->getStatus() == 'banned') {
                    return false;
                }
                else {
                    return false;
                }
            }
            else if ($request->request->get('mode') == 'ban') {

                // Ok que si (aucun status) ou si (actuellement banned jusqu'a une date inferieur à nouvelle date)
                if( (($author->getStatus() == 'banned') && (DateTime::createFromFormat('Y-m-d', $request->request->get('endDate')) > $author->getEndDateStatus())) || $author->getStatus() == "" ) {

                    // Maj Status et endDateStatus Author
                    $author->setStatus("banned");
                    $dateString = $request->request->get('endDate');
                    $date = DateTime::createFromFormat('Y-m-d', $dateString);
                    $author->setEndDateStatus($date);

                    // Envoi notif à l'Author (censure + Ban/Mute) et notifs "merci" aux reporters
                    $notifController->notifCensureAuthor($author, $objectType, $objectText);
                    $notifController->notifBanMuteAuthor("ban", $author, $date);
                    foreach ($objectReports as $report) {
                        $notifController->notifThxReporters($report->getUserReporter());
                    }

                    return true;
                }
                else {
                    return false; 
                }
            }

        }



        if( $this->getUser() && in_array('ROLE_MODO', $this->getUser()->getRoles()) ) {

            // Récupération des reports en relation avec l'objet (pour envoyer notif "merci")
            $reportRepo = $entityManager->getRepository(Report::class);
            $objectReports = $reportRepo->findBy(["objectType" => $objectType, "objectId" => $objectId]);

            // Censure de l'objet (Si Topic/Media: suppression + Children, Si postTopic/postMedia: setText('le comm a été suppr'))
            switch ($objectType) {

                case 'media':
                    
                    $mediaRepo = $entityManager->getRepository(Media::class);
                    $mediaReported = $mediaRepo->find($objectId);
                    $objectText = $mediaReported->getTitle();
                    $author = $mediaReported->getUser();
    
                    if(applySentence($request, $author, $this, $this->notifController, $objectType, $objectText, $objectReports)) {

                        $mediaRepo->remove($mediaReported, true);

                        // Suppr des reports associés
                        // $reportRepo = $entityManager->getRepository(report::class);
                        // $reports = $reportRepo->findBy(["objectType" => $objectType, "objectId" => $objectId]);
                        foreach ($objectReports as $report) {
                            $reportRepo->remove($report, true);
                        }

                    } else {
                        $this->addFlash('error', 'La pénalité est inférieur à celle déjà en place pour cet utilisateur, vous ne pouvez que l\'augmenter');
                        return $this->redirectToRoute('app_moderationDashboard');
                    }
            
                    break;
    
                case 'topic':
    
                    $topicRepo = $entityManager->getRepository(Topic::class);
                    $topicReported = $topicRepo->find($objectId);
                    $objectText = $topicReported->getTitle();
                    $author = $topicReported->getUser();
    
                    if(applySentence($request, $author, $this, $this->notifController, $objectType, $objectText, $objectReports)) {

                        $topicRepo->remove($topicReported, true);

                        // Suppr des reports associés
                        // $reportRepo = $entityManager->getRepository(report::class);
                        // $reports = $reportRepo->findBy(["objectType" => $objectType, "objectId" => $objectId]);
                        foreach ($objectReports as $report) {
                            $reportRepo->remove($report, true);
                        }
                    } 
                    else {
                        $this->addFlash('error', 'La pénalité est inférieur à celle déjà en place pour cet utilisateur, vous ne pouvez que l\'augmenter');
                        return $this->redirectToRoute('app_moderationDashboard');
                    }

                    break;

                case 'topicPost':

                    $topicPostRepo = $entityManager->getRepository(TopicPost::class);
                    $topicPostReported = $topicPostRepo->find($objectId);
                    $objectText = $topicPostReported->getText();
                    $author = $topicPostReported->getUser();
    
                    if(applySentence($request, $author, $this, $this->notifController, $objectType, $objectText, $objectReports)) {

                        // Attention: text brut utilisé dans TWIG pour savoir si pas de upvotes par exemple 
                        $topicPostReported->setText("Le commentaire a été supprimé");

                        $entityManager->persist($topicPostReported);
                        $entityManager->flush();

                        // Suppr des reports associés
                        // $reportRepo = $entityManager->getRepository(report::class);
                        // $reports = $reportRepo->findBy(["objectType" => $objectType, "objectId" => $objectId]);
                        foreach ($objectReports as $report) {
                            $reportRepo->remove($report, true);
                        }

                    }
                    else {
                        $this->addFlash('error', 'La pénalité est inférieur à celle déjà en place pour cet utilisateur, vous ne pouvez que l\'augmenter');
                        return $this->redirectToRoute('app_moderationDashboard');
                    }

                    break;

                case 'mediaPost':

                    $mediaPostRepo = $entityManager->getRepository(MediaPost::class);
                    $mediaPostReported = $mediaPostRepo->find($objectId);
                    $objectText = $mediaPostReported->getText();
                    $author = $mediaPostReported->getUser();
    
                    if(applySentence($request, $author, $this, $this->notifController, $objectType, $objectText, $objectReports)) {

                        // Attention: text brut utilisé dans TWIG pour savoir si pas de upvotes par exemple 
                        $mediaPostReported->setText("Le commentaire a été supprimé");

                        $entityManager->persist($mediaPostReported);
                        $entityManager->flush();

                        // Suppr des reports associés
                        // $reportRepo = $entityManager->getRepository(report::class);
                        // $reports = $reportRepo->findBy(["objectType" => $objectType, "objectId" => $objectId]);
                        foreach ($objectReports as $report) {
                            $reportRepo->remove($report, true);
                        }

                    }
                    else {
                        $this->addFlash('error', 'La pénalité est inférieur à celle déjà en place pour cet utilisateur, vous ne pouvez que l\'augmenter');
                        return $this->redirectToRoute('app_moderationDashboard');
                    }

                    break;
                
                default:
                    break;
            }

            
            $author->setNbrCensures($author->getNbrCensures() + 1);

            $entityManager->persist($author);
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
    #[Route('/validateTopic/{slug}', name: 'app_validateTopic')]
    public function validateTopic(EntityManagerInterface $entityManager, string $slug, Request $request): Response
    {
        if ( $this->getUser() && in_array('ROLE_MODO', $this->getUser()->getRoles()) ) {

            $topicRepo = $entityManager->getRepository(Topic::class);
            $topic = $topicRepo->findOneBy(['slug' => $slug]);
            
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
    #[Route('/refuseTopic/{slug}', name: 'app_refuseTopic')]
    public function refuseTopic(EntityManagerInterface $entityManager, string $slug, Request $request): Response
    {
        if ( $this->getUser() && in_array('ROLE_MODO', $this->getUser()->getRoles()) ) {

            $topicRepo = $entityManager->getRepository(Topic::class);
            $topic = $topicRepo->findOneBy(['slug' => $slug]);
            
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


    // Verrouillage du topic par un modérateur
    #[Route('/lockTopic/{slug}', name: 'app_lockTopic')]
    public function lockTopic(EntityManagerInterface $entityManager, string $slug, Request $request): Response
    {
        $topicRepo = $entityManager->getRepository(Topic::class);

        $topic = $topicRepo->findOneBy(['slug' => $slug]);

        // Vérif si user est bien modo
        if ( $this->getUser() && in_array('ROLE_MODO', $this->getUser()->getRoles()) ) {

            $topic->setStatus("closedModo");
            $entityManager->flush();

            // Notification à l'auteur
            $this->notifController->notifLockedTopic($topic->getUser(), $topic);

            $this->addFlash('success', 'Le topic a bien été verrouillé');
            return $this->redirectToRoute('app_topicDetail', ['slug' => $slug]); 
        }
        else {
            $this->addFlash('error', 'Vous devez être modérateur pour vérouiller un topic');
            return $this->redirectToRoute('app_topicDetail', ['slug' => $slug]); 
        }
    }



    // Déverrouillage du topic par un modérateur
    #[Route('/unlockTopic/{slug}', name: 'app_unlockTopic')]
    public function unlockTopic(EntityManagerInterface $entityManager, string $slug, Request $request): Response
    {
        $topicRepo = $entityManager->getRepository(Topic::class);

        $topic = $topicRepo->findOneBy(['slug' => $slug]);

        // Vérif si user est bien modo
        if ( $this->getUser() && in_array('ROLE_MODO', $this->getUser()->getRoles()) ) {

            $topic->setStatus("open");
            $entityManager->flush();

            // Notification à l'auteur
            $this->notifController->notifUnlockedTopic($topic->getUser(), $topic);

            $this->addFlash('success', 'Le topic a bien été déverrouillé et rouvert');
            return $this->redirectToRoute('app_topicDetail', ['slug' => $slug]); 
        }
        else {
            $this->addFlash('error', 'Vous devez être modérateur pour dévérouiller un topic');
            return $this->redirectToRoute('app_topicDetail', ['slug' => $slug]); 
        }
    }






    // Verrouillage du média par un modérateur
    #[Route('/lockMedia/{slug}', name: 'app_lockMedia')]
    public function lockMedia(EntityManagerInterface $entityManager, string $slug, Request $request): Response
    {
        $mediaRepo = $entityManager->getRepository(Media::class);

        $media = $mediaRepo->findOneBy(['slug' => $slug]);

        // Vérif si user est bien modo
        if ( $this->getUser() && in_array('ROLE_MODO', $this->getUser()->getRoles()) ) {

            $media->setStatus("closedModo");
            $entityManager->flush();

            // Notification à l'auteur
            $this->notifController->notifLockedMedia($media->getUser(), $media);

            $this->addFlash('success', 'Le média a bien été verrouillé');
            return $this->redirectToRoute('app_mediaDetail', ['slug' => $slug]); 
        }
        else {
            $this->addFlash('error', 'Vous devez être modérateur pour vérouiller un média');
            return $this->redirectToRoute('app_mediaDetail', ['slug' => $slug]); 
        }
    }



    // Déverrouillage du topic par un modérateur
    #[Route('/unlockMedia/{slug}', name: 'app_unlockMedia')]
    public function unlockMedia(EntityManagerInterface $entityManager, string $slug, Request $request): Response
    {
        $mediaRepo = $entityManager->getRepository(Media::class);

        $media = $mediaRepo->findOneBy(['slug' => $slug]);

        // Vérif si user est bien modo
        if ( $this->getUser() && in_array('ROLE_MODO', $this->getUser()->getRoles()) ) {

            $media->setStatus("open");
            $entityManager->flush();

            // Notification à l'auteur
            $this->notifController->notifUnlockedMedia($media->getUser(), $media);

            $this->addFlash('success', 'Le média a bien été déverrouillé');
            return $this->redirectToRoute('app_mediaDetail', ['slug' => $slug]); 
        }
        else {
            $this->addFlash('error', 'Vous devez être modérateur pour dévérouiller un média');
            return $this->redirectToRoute('app_mediaDetail', ['slug' => $slug]); 
        }
    }
    
    
    
}
