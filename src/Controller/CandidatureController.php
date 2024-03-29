<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\Candidature;
use App\Entity\Group;
use App\Entity\GroupQuestion;
use App\Entity\GroupAnswer;
use App\Entity\Game;
use App\Entity\Topic;
use App\Entity\Media;
use App\Entity\Report;
use App\Entity\User;
use App\Form\CandidatureType;
use App\Repository\GroupRepository;
use App\Repository\NotificationRepository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class CandidatureController extends AbstractController
{
    private $csrfTokenManager;
    private $notifController;

    public function __construct(NotificationController $notifController, CsrfTokenManagerInterface $csrfTokenManager) 
    {
        $this->notifController = $notifController;
        $this->csrfTokenManager = $csrfTokenManager;
    }


    // Affiche de la liste des candidature d'un groupe (leader)
    #[Route('/candidatureList/{groupSlug}', name: 'app_candidatureList')]
    public function candidatureList(EntityManagerInterface $entityManager, string $groupSlug, Request $request): Response
    {
        $groupRepo = $entityManager->getRepository(Group::class);
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

        $group = $groupRepo->findOneBy(['slug' => $groupSlug]);
        $gameFrom = $group->getGame();
        $candidatures = $group->getCandidatures();

        // if is leader
        if ($this->getUser() == $group->getLeader() ) {

            return $this->render('candidature/candidatureList.html.twig', [
                'modoNotifValidationCount' => $modoNotifValidationCount,
                'modoNotifReportCount' => $modoNotifReportCount,
                'userNotifCount' => $userNotifCount,
                'candidatures' => $candidatures,
                'group' => $group,
                'gameFrom' => $gameFrom,

            ]);
        }
        else {
            $this->addFlash('error', 'Vous devez être le leader pour accéder à cette page');
            return $this->redirectToRoute('app_groupDetails', ['groupSlug' => $group->getSlug()]);
        }

        
    }


    
    // Page Candidature détaillée (leader)
    #[Route('/candidatureDetails/{candidatureId}/{notifId}', name: 'app_candidatureDetails', defaults: ['notifId' => null])]
    public function candidatureDetails(EntityManagerInterface $entityManager, int $candidatureId, Request $request, int $notifId = null): Response
    {

        $groupRepo = $entityManager->getRepository(Group::class);
        $candidatureRepo = $entityManager->getRepository(Candidature::class);
        $groupQuestionRepo = $entityManager->getRepository(GroupQuestion::class);
        $groupAnswerRepo = $entityManager->getRepository(GroupAnswer::class);
        $notifRepo = $entityManager->getRepository(Notification::class);
        $mediaRepo = $entityManager->getRepository(Media::class);
        $topicRepo = $entityManager->getRepository(Topic::class);
        $reportRepo = $entityManager->getRepository(Report::class);

        // Si la candidature-cible de la notif n'existe plus (accpté/refusée/annulée)
        $candidature = $candidatureRepo->find($candidatureId);
        if(!is_null($candidature)) {

            // Si page vient de notifId, passe la notif en "clicked"
            if (!is_null($notifId)) {
                $notifFrom = $notifRepo->find($notifId);
                $notifFrom->setClicked(true);
                $entityManager->persist($notifFrom);
                $entityManager->flush();
            }

            // Verif user connecté et leader du groupe 
            if ($this->getUser()) {

                $group = $candidature->getGroupe();
                $gameFrom = $group->getGame();

                // if is leader
                if ($this->getUser() == $group->getLeader()) {

                    // Nombre de notifs "non-vues"
                    $userNotifCount = count($notifRepo->findByUserNotSeen($this->getUser()));
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

                    // Tableau assoc Question/Réponse (juste text)
                    $groupAnswers = $candidature->getGroupAnswers();
                    $questionData = [];
                    foreach ($groupAnswers as $answer) {
                        $associatedQuestion = $answer->getGroupQuestion()->getText();
                        $newTab = [$associatedQuestion, $answer->getText()];
                        $questionData[] = $newTab;
                    }

                    return $this->render('candidature/candidatureDetails.html.twig', [
                        'modoNotifValidationCount' => $modoNotifValidationCount,
                        'modoNotifReportCount' => $modoNotifReportCount,
                        'userNotifCount' => $userNotifCount,
                        'candidature' => $candidature,
                        'group' => $group,
                        'gameFrom' => $gameFrom,
                        'questionData' => $questionData,
                    ]);
                }
                else {
                    $this->addFlash('error', 'Vous devez être le leader pour accéder à cette page');
                    return $this->redirectToRoute('app_groupDetails', ['groupSlug' => $group->getSlug()]);
                }
            } else {
                $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page');
                return $this->redirectToRoute('app_groupDetails', ['groupSlug' => $group->getSlug()]);
            }
        } 
        else {

            $notifFrom = $notifRepo->find($notifId);
            $entityManager->remove($notifFrom);
            $entityManager->flush();

            $this->addFlash('error', 'La candidature n\'existe plus');
            return $this->redirectToRoute('app_showNotifsList');
        }

        
    }

    
    #[Route('/cancelCandidature/{candidatureId}', name: 'app_cancelCandidature')]
    public function cancelCandidature(EntityManagerInterface $entityManager, int $candidatureId, Request $request): Response
    {
        $candidatureRepo = $entityManager->getRepository(Candidature::class);
        $groupRepo = $entityManager->getRepository(Group::class);
        $candidature = $candidatureRepo->find($candidatureId);
        $group = $candidature->getGroupe();

        // Vérif si userCo est bien auteur de la candidature
        if ($candidature->getUser() == $this->getUser()) {

            $candidatureRepo->remove($candidature);
            $entityManager->flush();

            $this->addFlash('success', 'Candidature annulée');
            return $this->redirectToRoute('app_groupDetails', ['groupSlug' => $group->getSlug()]);
        }
        else {
            $this->addFlash('error', 'Vous devez être l\'auteur de la candidature pour l\'annuler');
            return $this->redirectToRoute('app_groupDetails', ['groupSlug' => $group->getSlug()]);
        }
    }


    // Leader: ajout membre
    #[Route('/acceptCandidature/{candidatureId}', name: 'app_acceptCandidature')]
    public function acceptCandidature(EntityManagerInterface $entityManager, int $candidatureId, Request $request): Response
    {
        $candidatureRepo = $entityManager->getRepository(Candidature::class);
        $groupRepo = $entityManager->getRepository(Group::class);
        $candidature = $candidatureRepo->find($candidatureId);
        $group = $candidature->getGroupe();

        // Vérif leader
        if ($this->getUser() == $group->getLeader()) {

            // Vérif si groupe plein
            if ($group->getNbrPlaces() <= count($group->getMembers())) {
                $this->addFlash('error', 'Le groupe est plein');
                return $this->redirectToRoute('app_groupDetails', ['groupSlug' => $group->getSlug()]);
            }
            else {
                $userToAdd = $candidature->getUser();
                $group->addMember($userToAdd);
    
                $candidatureRepo->remove($candidature);
                $entityManager->flush();

                // Notification au User
                $this->notifController->notifUpdateCandidature("accept", $candidature->getUser(), $group);
                // Notifs aux membres
                $this->notifController->notifNewMember($candidature->getUser(), $group);


                $this->addFlash('success', $candidature->getUser()->getPseudo() . ' a rejoint la team');
                return $this->redirectToRoute('app_groupDetails', ['groupSlug' => $group->getSlug()]);
            }
        }
        else {
            $this->addFlash('error', 'Vous devez être l\'auteur de la candidature pour l\'annuler');
            return $this->redirectToRoute('app_groupDetails', ['groupSlug' => $group->getSlug()]);
        }
    }


    // Leader: refus de candidature OU refus + blacklist
    #[Route('/rejectCandidature/{candidatureId}/{isBlacklisted}', name: 'app_rejectCandidature')]
    public function rejectCandidature(EntityManagerInterface $entityManager, int $candidatureId, string $isBlacklisted, Request $request): Response
    {
        $candidatureRepo = $entityManager->getRepository(Candidature::class);
        $groupRepo = $entityManager->getRepository(Group::class);
        $candidature = $candidatureRepo->find($candidatureId);
        $group = $candidature->getGroupe();

        // Vérif leader
        if ($this->getUser() == $group->getLeader()) {

            // Si btn "refus définitif": blacklist user
            if ($isBlacklisted == "true") {
                $group->addBlacklistedUser($candidature->getUser());
                $entityManager->persist($group);
            }
            
            $candidatureRepo->remove($candidature);
            $entityManager->flush();

            // Notification au User (ne précise pas si refus définitif, todo?)
            $this->notifController->notifUpdateCandidature("reject", $candidature->getUser(), $group);

            $this->addFlash('success', 'Vous avez refusé la candidature de ' . $candidature->getUser()->getPseudo());
            return $this->redirectToRoute('app_groupDetails', ['groupSlug' => $group->getSlug()]);
            
        }
        else {
            $this->addFlash('error', 'Vous devez être leader de la team pour refuser un candidat');
            return $this->redirectToRoute('app_groupDetails', ['groupSlug' => $group->getSlug()]);
        }
    }


    // Affichage de la page de candidature (form)
    #[Route('/showCandidatureForm/{groupSlug}', name: 'app_showCandidatureForm')]
    public function showCandidatureForm(EntityManagerInterface $entityManager, string $groupSlug, Request $request): Response
    {
        $groupRepo = $entityManager->getRepository(Group::class);
        $notifRepo = $entityManager->getRepository(Notification::class);
        $mediaRepo = $entityManager->getRepository(Media::class);
        $topicRepo = $entityManager->getRepository(Topic::class);
        $reportRepo = $entityManager->getRepository(Report::class);

        $group = $groupRepo->findOneBy(['slug' => $groupSlug]);

        // Check si groupe plein
        if(count($group->getMembers()) < $group->getNbrPlaces()) {

            // Check si team publique
            if($group->getStatus() == "public") {

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

                $gameFrom = $group->getGame();
                $groupQuestions = $group->getGroupQuestions();

                if(is_null($this->getUser())) {
                    $this->addFlash('error', 'Vous devez être connecté pour candidater');
                    return $this->redirectToRoute('app_login'); 
                }

                // check si pas deja membre
                if( !$group->getMembers()->contains($this->getUser()) ) {

                    // check si pas blacklisted
                    $userToCheck = $this->getUser();
                    $groupId = $group->getId();
                    
                    $connection = $entityManager->getConnection();
                    $statement = $connection->prepare('
                        SELECT COUNT(*) AS count
                        FROM group_blacklist
                        WHERE group_id = :groupId
                        AND user_id = :userId
                    ');
                    $statement->bindValue('groupId', $groupId);
                    $statement->bindValue('userId', $userToCheck->getId());
                    $result = $statement->executeQuery()->fetchAssociative();
                    
                    $isBlacklisted = $result['count'] > 0;
                    if ($isBlacklisted) {
                        $this->addFlash('error', 'Vous ne pouvez plus candidater pour cette team');
                        return $this->redirectToRoute('app_groupDetails', ['groupSlug' => $groupSlug]); 
                    }

                    // check si candidature existe deja 
                    $candidatureRepo = $entityManager->getRepository(Candidature::class);
                    $countExist = count($candidatureRepo->findBy(["user" => $this->getUser(), "groupe" => $group]));
                    if ($countExist == 0) {

                        $candidature = new Candidature;
                        $form = $this->createForm(CandidatureType::class, $candidature);
                        $form -> handleRequest($request);

                        // Vérifs/Filtres
                        if($form->isSubmitted()) {

                            // refresh CSRF token (form_intention) (avoid multiple form submission)
                            $this->csrfTokenManager->refreshToken("form_intention");

                            if($form->isValid()) {

                                $candidature = $form->getData();

                                // Vérif confirmation majorité si critère group coché:
                                if($group->isRestriction18()) {

                                    if($request->request->get('majorityBool') == false || is_null($request->request->get('majorityBool'))) {

                                        $this->addFlash('error', 'Vous devez confirmer être majeur pour candidater à cette team');
                                        return $this->redirectToRoute('app_showCandidatureForm', ['groupSlug' => $groupSlug]); 
                                    }
                                }
                                    

                                // Vérif si Texte de candidature répondu (obligatoire) (testé)
                                if($candidature->getText() == "") {
                                    $this->addFlash('error', 'Vous n\'avez pas rempli votre introduction');
                                    return $this->redirectToRoute('app_showCandidatureForm', ['groupSlug' => $groupSlug]); 
                                }

                                // Associez chaque GroupAnswer à chaque GroupQuestion (candidature form)
                                $index = 0;
                                foreach ($groupQuestions as $groupQuestion) {

                                    $index++;
                                    $idQuestion = $groupQuestion->getId();

                                    // Vérif si questions obligatoires répondues (testé)
                                    if ($groupQuestion->isRequired() && $request->request->get('answer' . $index) == "" ) {
                                        $this->addFlash('error', 'Vous n\'avez pas répondu à toutes les questions obligatoires');
                                        return $this->redirectToRoute('app_showCandidatureForm', ['groupSlug' => $groupSlug]); 
                                    }
                                    
                                    $answer = new GroupAnswer;
                                    $answer->setCandidature($candidature);
                                    $answer->setText($request->request->get('answer' . $index));
                                    $answer->setGroupQuestion($groupQuestion);
                                    $candidature->addGroupAnswer($answer);

                                    $entityManager->persist($answer);
                                }
                                // Ou alors créer un groupReponse pour chaque input à l'ancienne + verif si exite '

                                $candidature->setUser($this->getUser());
                                $candidature->setGroupe($group);
                                $candidature->setCreationDate(new \Datetime());
                                $candidature->setStatus("pending");

                                $entityManager->persist($candidature);
                                $entityManager->flush();

                                // Notif newCandidature au leader de la team 
                                $this->notifController->notifNewCandidature($group->getLeader(), $candidature);

                                $this->addFlash('success', 'Votre candidature a bien été envoyée');
                                return $this->redirectToRoute('app_groupDetails', ['groupSlug' => $groupSlug]); 
                            }
                        }

                        return $this->render('candidature/groupCandidatureForm.html.twig', [
                            'modoNotifValidationCount' => $modoNotifValidationCount,
                            'modoNotifReportCount' => $modoNotifReportCount,
                            'userNotifCount' => $userNotifCount,
                            'formCandidature' => $form->createView(),
                            'group' => $group,
                            'gameFrom' => $gameFrom,
                            'groupQuestions' => $groupQuestions,
                        ]);

                    }
                    else {
                        $this->addFlash('error', 'Vous avez déjà candidaté à cette team');
                        return $this->redirectToRoute('app_groupDetails', ['groupSlug' => $groupSlug]);
                    }
                }
                else {
                    $this->addFlash('error', 'Vous êtes déjà membre de cette team ou n\'êtes pas connecté');
                    return $this->redirectToRoute('app_groupDetails', ['groupSlug' => $groupSlug]);
                }
            }
            else {
                $this->addFlash('error', 'La team n\'est pas publique');
                return $this->redirectToRoute('app_groupDetails', ['groupSlug' => $groupSlug]);
            }
        }
        else {
            $this->addFlash('error', 'La team est pleine');
            return $this->redirectToRoute('app_groupDetails', ['groupSlug' => $groupSlug]);
        }
    }
}
