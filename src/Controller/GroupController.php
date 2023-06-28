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
use App\Entity\User;
use App\Form\GroupType;
use App\Form\CandidatureType;
use App\Repository\GroupRepository;
use App\Repository\NotificationRepository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class GroupController extends AbstractController
{

    private $notifController;

    public function __construct(NotificationController $notifController) {

        $this->notifController = $notifController;
    }


    // Creation Team: Id Game
    #[Route('/createGroup/{gameIdFrom}', name: 'app_createGroup')]
    public function createGroup(EntityManagerInterface $entityManager, int $gameIdFrom, Request $request): Response
    {    
        $gameRepo = $entityManager->getRepository(Game::class);
        $notifRepo = $entityManager->getRepository(Notification::class);
        $mediaRepo = $entityManager->getRepository(Media::class);
        $topicRepo = $entityManager->getRepository(Topic::class);

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

        $gameFrom = $gameRepo->find($gameIdFrom);

        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);
        $form -> handleRequest($request);

        // Vérifs/Filtres
        if($form->isSubmitted()) {

            // Vérif connecté pour créer un Group
            if($this->getUser()) {
                    
                    if($form->isValid()) {

                        // Hydrataion du "Group" a partir des données du form
                        $group = $form->getData();

                        // Vérif au moins 2 places
                        if($group->getNbrPlaces() < 2) {
                            $this->addFlash('error', 'Le groupe doit avoir au moins 2 places');
                            return $this->redirectToRoute('app_createGroup', ['gameIdFrom' => $gameFrom->getId()]);
                        }
    
                        // Init de la publish_date du comment
                        $group->setCreationDate(new \DateTime());
                        $group->setLeader($this->getUser());
                        $group->setGame($gameFrom);
                        $group->addMember($this->getUser());

                        // Paramètres du Group
                        $isPublicChecked = $group->getStatus();
                        if($isPublicChecked) {
                            $group->setStatus("public"); 
                        } else {
                            $group->setStatus("hidden"); 
                        }

                        $isMicChecked = $group->isRestrictionMic();
                        if($isMicChecked) {
                            $group->setRestrictionMic(true); 
                        } else {
                            $group->setRestrictionMic(false); 
                        }

                        $is18Checked = $group->isRestriction18();
                        if($is18Checked) {
                            $group->setRestriction18(true); 
                        } else {
                            $group->setRestriction18(false); 
                        }

                        $isImgProofChecked = $group->isRestrictionImgProof();
                        if($isImgProofChecked) {
                            $group->setRestrictionImgProof(true); 
                        } else {
                            $group->setRestrictionImgProof(false); 
                        }
                        
                        // Désactivation vérification nbr de mots etc...
                        // // Récupération du titre
                        // $textInputValue = $form->get('text')->getData();
                        // // Liste des mots du commentaires
                        // $words = str_word_count($textInputValue, 1);
                        // // Décompte du nombre de mots dans la liste
                        // $wordCount = count($words);
                        // // Vérification du compte de mots
                        // if ($wordCount >= 5) {
    
                        $entityManager->persist($group);
                        $entityManager->flush();

                        $this->addFlash('success', 'Le groupe a bien été créé');
                        return $this->redirectToRoute('app_groupDetails', ['groupId' => $group->getId()]);
                    } 
                    else {
                        $this->addFlash('error', 'Pas de vulgarités pour un titre');
                        return $this->redirectToRoute('app_groupDetails', ['groupId' => $group->getId()]);
                    }  
            }
            else {
                $this->addFlash('error', 'Vous devez être connecté pour créer un groupe');
                return $this->redirectToRoute('app_login');
            }
        }
        return $this->render('group/createGroup.html.twig', [
            'modoNotifCount' => $modoNotifCount,
            'userNotifCount' => $userNotifCount,
            'formAddGroup' => $form->createView(),
            'gameFrom' => $gameFrom,
        ]);
    }


    // Liste des Teams (publiques!): Id Game
    #[Route('/groupList/{gameIdFrom}', name: 'app_groupList')]
    public function groupList(EntityManagerInterface $entityManager, int $gameIdFrom, Request $request): Response
    {
        $gameRepo = $entityManager->getRepository(Game::class);
        $notifRepo = $entityManager->getRepository(Notification::class);
        $mediaRepo = $entityManager->getRepository(Media::class);
        $topicRepo = $entityManager->getRepository(Topic::class);

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

        $gameFrom = $gameRepo->find($gameIdFrom);

        // Toutes les teams du jeu (publiques et orderBy)
        $groupRepo = $entityManager->getRepository(Group::class);
        $groups = $groupRepo->findAllByGame($gameFrom); // where "public" ok

        return $this->render('group/groupList.html.twig', [
            'modoNotifCount' => $modoNotifCount,
            'userNotifCount' => $userNotifCount,
            'gameFrom' => $gameFrom,
            'groups' => $groups,
        ]);
    }



    // Detail du groupe: Id Group
    #[Route('/groupDetails/{groupId}/{notifId}', name: 'app_groupDetails')]
    public function groupDetails(EntityManagerInterface $entityManager, int $groupId, Request $request, int $notifId = null): Response
    {
        $groupRepo = $entityManager->getRepository(Group::class);
        $notifRepo = $entityManager->getRepository(Notification::class);
        $mediaRepo = $entityManager->getRepository(Media::class);
        $topicRepo = $entityManager->getRepository(Topic::class);

        // Si le group-cible de la notif n'existe plus
        $group = $groupRepo->find($groupId);
        if(!is_null($group)) {

            // Si page vient de notifId, passe la notif en "clicked"
            if (!is_null($notifId)) {
                $notifFrom = $notifRepo->find($notifId);
                $notifFrom->setClicked(true);
                $entityManager->persist($notifFrom);
                $entityManager->flush();
            }

            // Nombre d'user blacklistés
            $blacklistedNbr = count($group->getBlacklistedUsers());
                    
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

            
            // Vérif si groupe public (todo)
            if ($group->getStatus() == "public") {
                $game = $group->getGame();
                $members = $group->getMembers();
                $questions = $group->getGroupQuestions();
                $candidatureCount = count($group->getCandidatures());

                $candidatureRepo = $entityManager->getRepository(Candidature::class);
                $waitingCandidatures = count($candidatureRepo->findBy(["user" => $this->getUser(), "groupe" => $group, "status" => "pending"]));
                $candidature = $candidatureRepo->findOneBy(["user" => $this->getUser(), "groupe" => $group]);

                if ($waitingCandidatures > 0) {
                    $waitingCandidature = true;
                }
                else {
                    $waitingCandidature = false;
                }

                return $this->render('group/groupDetails.html.twig', [
                    'modoNotifCount' => $modoNotifCount,
                    'userNotifCount' => $userNotifCount,
                    'group' => $group,
                    'gameFrom' => $game,
                    'members' => $members,
                    'questions' => $questions,
                    'waitingCandidature' => $waitingCandidature,
                    'candidatureCount' => $candidatureCount,
                    'candidature' => $candidature,
                    'blacklistedNbr' => $blacklistedNbr,
                ]);
            }
            else {
                $this->addFlash('error', 'La team n\'est pas publique');
                return $this->redirectToRoute('app_groupList', ['gameIdFrom' => $game->getId()]);
            }
        }
        else {
            $this->addFlash('error', 'La team n\'existe plus');
            return $this->redirectToRoute('app_showNotifsList');
        }
    }



    // Leader: Blacklist du group
    #[Route('/groupBlacklist/{groupId}', name: 'app_groupBlacklist')]
    public function groupBlacklist(EntityManagerInterface $entityManager, int $groupId, Request $request): Response
    {
        $groupRepo = $entityManager->getRepository(Group::class);
        $notifRepo = $entityManager->getRepository(Notification::class);
        $mediaRepo = $entityManager->getRepository(Media::class);
        $topicRepo = $entityManager->getRepository(Topic::class);

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

        $group = $groupRepo->find($groupId);
        $game = $group->getGame();
        
        // Vérif si leader
        if ($group->getLeader() == $this->getUser()) {
            
            return $this->render('group/blacklist.html.twig', [
                'modoNotifCount' => $modoNotifCount,
                'userNotifCount' => $userNotifCount,
                'group' => $group,
                'gameFrom' => $game,
            ]);
        }
        else {
            $this->addFlash('error', 'Vous devez être le leader pour accéder à cette page');
            return $this->redirectToRoute('app_groupList', ['gameIdFrom' => $game->getId()]);
        }
    }

    // Suppr User de la BlackList
    #[Route('/removeFromBlacklist/{groupId}/{userId}', name: 'app_removeFromBlacklist')]
    public function removeFromBlacklist(EntityManagerInterface $entityManager, int $groupId, int $userId, Request $request): Response
    {
        $groupRepo = $entityManager->getRepository(Group::class);
        $userRepo = $entityManager->getRepository(User::class);
        $group = $groupRepo->find($groupId);
        $targetedUser = $userRepo->find($userId);
        
        // Vérif si leader
        if ($group->getLeader() == $this->getUser()) {
            
            $group->removeBlacklistedUser($targetedUser);
            $entityManager->persist($group);
            $entityManager->flush();

            $this->addFlash('success', 'Vous avez retiré ' . $targetedUser->getPseudo() . ' de la blacklist');
            return $this->redirectToRoute('app_groupBlacklist', ['groupId' => $group->getId()]);
        }
        else {
            $this->addFlash('error', 'Vous devez être le leader pour faire ceci');
            return $this->redirectToRoute('app_groupList', ['gameIdFrom' => $game->getId()]);
        }
    }


    
    #[Route('/emptyBlacklist/{groupId}', name: 'app_emptyBlacklist')]
    public function emptyBlacklist(EntityManagerInterface $entityManager, int $groupId, Request $request): Response
    {
        $groupRepo = $entityManager->getRepository(Group::class);
        $group = $groupRepo->find($groupId);
        
        // Vérif si leader
        if ($group->getLeader() == $this->getUser()) {

            $blacklistedUsers = $group->getBlacklistedUsers();
            foreach($blacklistedUsers as $blacklistedUser) {
                $group->removeBlacklistedUser($blacklistedUser);
            };
            $entityManager->flush();

            $this->addFlash('success', 'Vous avez nettoyé la blacklist');
            return $this->redirectToRoute('app_groupBlacklist', ['groupId' => $group->getId()]);
        }
        else {
            $this->addFlash('error', 'Vous devez être le leader pour faire ceci');
            return $this->redirectToRoute('app_groupList', ['gameIdFrom' => $game->getId()]);
        }
    }



    // Liste des groupes de l'userConnected
    #[Route('/userGroups', name: 'app_userGroups')]
    public function userGroups(EntityManagerInterface $entityManager): Response
    {
        // Groupes user incluant les privés
        $groupRepo = $entityManager->getRepository(Group::class);
        $notifRepo = $entityManager->getRepository(Notification::class);
        $mediaRepo = $entityManager->getRepository(Media::class);
        $topicRepo = $entityManager->getRepository(Topic::class);

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

        $groups = $this->getUser()->getGroupes();
        // $groups = $groupRepo->findUserGroups($this->getUser());


        return $this->render('group/userGroups.html.twig', [
            'modoNotifCount' => $modoNotifCount,
            'userNotifCount' => $userNotifCount,
            'groups' => $groups,
        ]);
    }



    // Quitter le groupe (userConnected) (Id Group) 
    #[Route('/leaveGroup/{groupId}', name: 'app_leaveGroup')]
    public function leaveGroup(EntityManagerInterface $entityManager, int $groupId, Request $request): Response
    {
        // Retire le membre du groupe
        $groupRepo = $entityManager->getRepository(Group::class);
        $group = $groupRepo->find($groupId);
        $game = $group->getGame();
        $group->removeMember($this->getUser());

        // Persist intermédiaire pour empecher repasser le lead random au user
        $entityManager->persist($group);

        // Notif aux autres membres (group(+membres), leaver)
        $this->notifController->notifMemberLeave($group, $this->getUser());

        // Si après le leave, il reste des membre et que l'User était leader, passe le lead, si plus aucun membre: suppr le groupe
        $userRepo = $entityManager->getRepository(User::class);
        $members = $group->getMembers();

        if ($members->count() > 0) {
            if ( $group->getLeader() == $this->getUser() ) {

                $membersArray = $members->toArray();
                $randomIndex = array_rand($membersArray);
                $randomMember = $membersArray[$randomIndex];
                $group->setLeader($randomMember);
                // Envoi notif au nouveau leader
                $this->notifController->notifNewLeader($randomMember, $group);

                $entityManager->persist($group);

                $entityManager->flush(); 

                return $this->redirectToRoute('app_groupDetails', ['groupId' => $group->getId()]);
            }
        // Si apprès le leave, aucun membre: suppr le groupe
        } 
        else {
            $groupRepo->remove($group);
        }

        $entityManager->flush(); 

        return $this->redirectToRoute('app_groupList', ['gameIdFrom' => $game->getId()]);
    }


    
    // Ajax Asynch toggleGroupVisibility (A fixer! juste inversion bool BDD pour l'instant)
    #[Route('/toggleGroupVisibility/{groupId}', name: 'app_toggleGroupVisibility')]
    public function toggleGroupVisibility(EntityManagerInterface $entityManager, int $groupId, Request $request): Response
    {
        $groupRepo = $entityManager->getRepository(Group::class);
        $group = $groupRepo->find($groupId);

        // check si user = leader 
        if ($group->getLeader() == $this->getUser() ) {

            if ($group->getStatus() == "public") {
                $group->setStatus("hidden");
                $newState = "La team est désormais cachée";
            }
            else if ($group->getStatus() == "hidden") {
                $group->setStatus("public");
                $newState = "Le team est désormais publique et apparait dans les recherches et les listes";
            }

            $entityManager->persist($group);
            $entityManager->flush();

            return new JsonResponse(['success' => true, "newState" => $newState]); 
        }
        else {
            return new JsonResponse(['success' => false]); 
        }
    }


    
    // Ajax Asynch toggleGroupRestriction18 (A fixer! juste inversion bool BDD pour l'instant)
    #[Route('/toggleGroupRestriction18/{groupId}', name: 'app_toggleGroupRestriction18')]
    public function toggleGroupRestriction18(EntityManagerInterface $entityManager, int $groupId, Request $request): Response
    {
        $groupRepo = $entityManager->getRepository(Group::class);
        $group = $groupRepo->find($groupId);

        // check si user = leader 
        if ($group->getLeader() == $this->getUser() ) {

            if ($group->isRestriction18()) {
                $group->setRestriction18(false);
                $newState = "La team est désormais ouverte aux personnes mineures";
            }
            else {
                $group->setRestriction18(true);
                $newState = "La team n'accepte désormais plus personnes mineures";
            }

            $entityManager->persist($group);
            $entityManager->flush();

            return new JsonResponse(['success' => true, "newState" => $newState]); 
        }
        else {
            return new JsonResponse(['success' => false]); 
        }
    }



    // Ajax Asynch toggleGroupRestrictionMic (A fixer! juste inversion bool BDD pour l'instant)
    #[Route('/toggleGroupRestrictionMic/{groupId}', name: 'app_toggleGroupRestrictionMic')]
    public function toggleGroupRestrictionMic(EntityManagerInterface $entityManager, int $groupId, Request $request): Response
    {
        $groupRepo = $entityManager->getRepository(Group::class);
        $group = $groupRepo->find($groupId);

        // check si user = leader 
        if ($group->getLeader() == $this->getUser() ) {

            if ($group->isRestrictionMic()) {
                $group->setRestrictionMic(false);
                $newState = "Le micro n'est désormais plus obligatoire pour candidater";
            }
            else {
                $group->setRestrictionMic(true);
                $newState = "Le micro est désormais obligatoire pour candidater";
            }

            $entityManager->persist($group);
            $entityManager->flush();

            return new JsonResponse(['success' => true, "newState" => $newState]); 
        }
        else {
            return new JsonResponse(['success' => false]); 
        }
    }


    // Ajax Asynch toggleRestrictionImgProof (A fixer! juste inversion bool BDD pour l'instant)
    #[Route('/toggleRestrictionImgProof/{groupId}', name: 'app_toggleRestrictionImgProof')]
    public function toggleRestrictionImgProof(EntityManagerInterface $entityManager, int $groupId, Request $request): Response
    {
        $groupRepo = $entityManager->getRepository(Group::class);
        $group = $groupRepo->find($groupId);

        // check si user = leader 
        if ($group->getLeader() == $this->getUser() ) {

            if ($group->isRestrictionImgProof()) {
                $group->setRestrictionImgProof(false);
                $newState = "L'upload de pièce jointe est désormais autorisé";
            }
            else {
                $group->setRestrictionImgProof(true);
                $newState = "L'upload de pièce jointe n'est désormais plus autorisé";
            }

            $entityManager->persist($group);
            $entityManager->flush();

            return new JsonResponse(['success' => true, "newState" => $newState]); 
        }
        else {
            return new JsonResponse(['success' => false]); 
        }
    }



    // Leader: passe le lead a un membre (select)
    #[Route('/switchTeamLeader/{groupId}', name: 'app_switchTeamLeader')]
    public function switchTeamLeader(EntityManagerInterface $entityManager, int $groupId, Request $request): Response
    {
        $groupRepo = $entityManager->getRepository(Group::class);
        $group = $groupRepo->find($groupId);

        // check si user = leader 
        if ($group->getLeader() == $this->getUser() ) {

            if(($request->request->get('memberId') == "")) {
                $this->addFlash('error', 'Vous n"avez pas choisi de membre');
                return $this->redirectToRoute('app_groupDetails', ['groupId' => $groupId]); 
            }

            $memberId = $request->request->get('memberId');
            $userRepo = $entityManager->getRepository(User::class);
            $userTarget = $userRepo->find($memberId);
            $userTargetPseudo = $userTarget->getPseudo();

            if ($group->getMembers()->contains($userTarget)) {
                $group->setLeader($userTarget);

                $entityManager->persist($group);
                $entityManager->flush();

                // Notifs différentes au nouveau Leader et aux autres membres 
                $this->notifController->notifNewLeader($userTarget, $group);

                $this->addFlash('success', 'Vous avez nommé ' . $userTarget->getPseudo() . ' leader de la team');
                return $this->redirectToRoute('app_groupDetails', ['groupId' => $groupId]);
            }
            else {
                $this->addFlash('error', 'L\'utilisateur doit être membre pour être promu');
                return $this->redirectToRoute('app_groupDetails', ['groupId' => $groupId]); 
            }
            
        }
        else {
            $this->addFlash('error', 'Vous devez être leader du groupe pour passer le lead');
            return $this->redirectToRoute('app_groupDetails', ['groupId' => $groupId]); 
        }
    }



    // // Leader: ajouter une question candidature
    #[Route('/addGroupQuestion/{groupId}', name: 'app_addGroupQuestion')]
    public function addGroupQuestion(EntityManagerInterface $entityManager, int $groupId, Request $request): Response
    {
        $groupRepo = $entityManager->getRepository(Group::class);
        $group = $groupRepo->find($groupId);
        $groupQuestion = $entityManager->getRepository(GroupQuestion::class);
        $groupQuestionCount = count($groupQuestion->findBy(["groupe" => $group]));

        // Si groupe a pas déjà 5 questions
        if($groupQuestionCount < 5) {

            $groupQuestion = new GroupQuestion;

            // check si user = leader 
            if ($group->getLeader() == $this->getUser() ) {
    
                if( !is_null($request->request->get('questionText')) ) {
                    $groupQuestion->setText($request->request->get('questionText'));
                    $groupQuestion->setGroupe($group);
    
                    if($request->request->get('required') == "checked") {
                        $groupQuestion->setRequired(true);
                    }
                    else {
                        $groupQuestion->setRequired(false);
                    }
    
                    $entityManager->persist($groupQuestion);
                    $entityManager->flush();
    
                    $this->addFlash('success', 'La question a été ajouté');
                    return $this->redirectToRoute('app_groupDetails', ['groupId' => $groupId]);
                }
                else {
                    $this->addFlash('error', 'Vous devez entrer une question');
                    return $this->redirectToRoute('app_groupDetails', ['groupId' => $groupId]); 
                }
                
            }
            else {
                $this->addFlash('error', 'Vous devez être leader du groupe pour ajouter une question');
                return $this->redirectToRoute('app_groupDetails', ['groupId' => $groupId]); 
            }
    
        }
        else {
            $this->addFlash('error', 'Vous avez atteint la limite de question autorisée');
            return $this->redirectToRoute('app_groupDetails', ['groupId' => $groupId]); 
        }
    }

    

    // // Leader: surrpimer une question candidature
    #[Route('/deleteGroupQuestion/{groupId}/{questionId}', name: 'app_deleteGroupQuestion')]
    public function deleteGroupQuestion(EntityManagerInterface $entityManager, int $groupId, int $questionId, Request $request): Response
    {
        $groupRepo = $entityManager->getRepository(Group::class);
        $group = $groupRepo->find($groupId);
        $groupQuestionRepo = $entityManager->getRepository(GroupQuestion::class);

        $groupQuestion = $groupQuestionRepo->find($questionId);

        // check si user = leader 
        if ($group->getLeader() == $this->getUser() ) {

            $groupQuestionRepo->remove($groupQuestion);
            $entityManager->flush();

            $this->addFlash('success', 'La question a été supprimé');
            return $this->redirectToRoute('app_groupDetails', ['groupId' => $groupId]);
            
        }
        else {
            $this->addFlash('error', 'Vous devez être leader du groupe pour supprimer une question');
            return $this->redirectToRoute('app_groupDetails', ['groupId' => $groupId]); 
        }
    }


    
    // Leader: Kick un membre du groupe
    #[Route('/kickGroupMember/{memberId}/{groupId}', name: 'app_kickGroupMember')]
    public function kickGroupMember(EntityManagerInterface $entityManager, int $memberId, int $groupId, Request $request): Response
    {
        $groupRepo = $entityManager->getRepository(Group::class);
        $group = $groupRepo->find($groupId);

        $userRepo = $entityManager->getRepository(User::class);
        $userKicked = $userRepo->find($memberId);

        // check si user = leader 
        if($group->getLeader() == $this->getUser() ) {

            // Controle leader != kickedUser
            if($userKicked == $this->getUser()) {
                $this->addFlash('error', 'Vous ne pouvez pas vous expulser de votre groupe, vous devez le quitter');
                return $this->redirectToRoute('app_groupDetails', ['groupId' => $groupId]);
            }

            $group->removeMember($userKicked);

            $entityManager->persist($group);
            $entityManager->flush();

            // Notif différente au membre expulsé et aux autre membres
            $this->notifController->notifKickedFromGroup($group, $userKicked);
            // Déplacé dans notifKickedFromGroup() "$this->notifController->notifMemberLeave($group, $userKicked);"

            $this->addFlash('success', 'Vous avez expulser ' . $userKicked->getPseudo() . ' de la team');
            return $this->redirectToRoute('app_groupDetails', ['groupId' => $groupId]);
        }
        else {
            $this->addFlash('error', 'Vous devez être leader du groupe pour expluser un membre');
            return $this->redirectToRoute('app_groupDetails', ['groupId' => $groupId]); 
        }
    }






}

