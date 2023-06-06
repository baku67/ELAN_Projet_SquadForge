<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\Game;
use App\Entity\User;
use App\Form\GroupType;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\HttpFoundation\JsonResponse;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class GroupController extends AbstractController
{
    // Creation Team: Id Game
    #[Route('/createGroup/{gameIdFrom}', name: 'app_createGroup')]
    public function createGroup(EntityManagerInterface $entityManager, int $gameIdFrom, Request $request): Response
    {    
        $gameRepo = $entityManager->getRepository(Game::class);
        $gameFrom = $gameRepo->find($gameIdFrom);

        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);
        $form -> handleRequest($request);

        // Vérifs/Filtres
        if($form->isSubmitted()) {

            // Vérif connecté pour créer un Group
            if($this->getUser()) {

                // Vérification si le group est ouvert
                // if ($group->getStatus() == "public") {
                    
                    if($form->isValid()) {

                        // Hydrataion du "Group" a partir des données du form
                        $group = $form->getData();
    
                        // Init de la publish_date du comment
                        $group->setCreationDate(new \DateTime());
                        $group->setLeader($this->getUser());
                        $group->setGame($gameFrom);
                        $group->setStatus("public"); // Demander dans form
                        $group->addMember($this->getUser());
                        
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
                        $entityManager->persist($group);
                        $entityManager->flush();

                        $this->addFlash('success', 'Le groupe a bien été créé');
                        return $this->redirectToRoute('app_groupDetails', ['groupId' => $group->getId()]);
                        // } else {
                            
                        //     $this->addFlash('error', 'Le titre doit faire au minimum 5 mots !');
                        //     return $this->redirectToRoute('app_game', ['id' => $game->getId()]);
                        // }
    
                    } 
                    else {
                        $this->addFlash('error', 'Pas de vulgarités pour un titre');
                        return $this->redirectToRoute('app_topicDetail', ['id' => $group->getId()]);
                    }   

                // }
                // else {
                //     $this->addFlash('error', 'Le Group est privé, vous devez être membre pour accéder à cette page.');
                //     return $this->redirectToRoute('app_groupList', ['gameIdFrom' => $group->getGame()->getId()]);
                // }


                
            }
            else {
                $this->addFlash('error', 'Vous devez être connecté pour créer un groupe');
                return $this->redirectToRoute('app_login');
            }
        }


        return $this->render('group/createGroup.html.twig', [
            'formAddGroup' => $form->createView(),
            'gameFrom' => $gameFrom,
        ]);
    }


    // Liste des Teams (publiques!): Id Game
    #[Route('/groupList/{gameIdFrom}', name: 'app_groupList')]
    public function groupList(EntityManagerInterface $entityManager, int $gameIdFrom, Request $request): Response
    {
        $gameRepo = $entityManager->getRepository(Game::class);
        $gameFrom = $gameRepo->find($gameIdFrom);

        // Toutes les teams du jeu (publiques et orderBy)
        $groupRepo = $entityManager->getRepository(Group::class);
        $groups = $groupRepo->findAllByGame($gameFrom); 

        return $this->render('group/groupList.html.twig', [
            'gameFrom' => $gameFrom,
            'groups' => $groups,
        ]);
    }


    // Detail du groupe: Id Group
    #[Route('/groupDetails/{groupId}', name: 'app_groupDetails')]
    public function groupDetails(EntityManagerInterface $entityManager, int $groupId, Request $request): Response
    {
        // find group id, game associé
        $groupRepo = $entityManager->getRepository(Group::class);
        $group = $groupRepo->find($groupId);
        $members = $group->getMembers();

        $game = $group->getGame();

        // Vérifs si group bien public


        return $this->render('group/groupDetails.html.twig', [
            'group' => $group,
            'gameFrom' => $game,
            'members' => $members,
        ]);
    }


    // Liste des groupes de l'userConnected
    #[Route('/userGroups', name: 'app_userGroups')]
    public function userGroups(EntityManagerInterface $entityManager): Response
    {
        // Groupes user incluant les privés
        $groupRepo = $entityManager->getRepository(Group::class);
        $groups = $groupRepo->findUserGroups($this->getUser());


        return $this->render('group/userGroups.html.twig', [
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

        // Si après le leave, il reste des membre et que l'User était leader, passe le lead, si plus aucun membre: suppr le groupe
        $userRepo = $entityManager->getRepository(User::class);
        $members = $group->getMembers();
        if ($members->count() > 0) {
            if ( $group->getLeader() == $this->getUser() ) {

                // $group->setLeader($members->random());
                $membersArray = $members->toArray();
                $randomIndex = array_rand($membersArray);
                $randomMember = $membersArray[$randomIndex];
                $group->setLeader($randomMember);

                $entityManager->persist($group);

                $entityManager->flush(); 

                return $this->render('group/groupDetails.html.twig', [
                    'group' => $group,
                    'gameFrom' => $game,
                    'members' => $members,
                ]);
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
                $newState = "cachée";
            }
            else if ($group->getStatus() == "hidden") {
                $group->setStatus("public");
                $newState = "publique";
            }

            $entityManager->persist($group);
            $entityManager->flush();

            return new JsonResponse(['success' => true, "newState" => $newState]); 
        }
        else {
            return new JsonResponse(['success' => false]); 
        }


    }



    

}

