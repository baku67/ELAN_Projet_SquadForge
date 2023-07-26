<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\User;
use App\Entity\GroupSession;
use App\Entity\GroupSessionDispo;

use App\Repository\GroupRepository;
use App\Repository\GroupSessionRepository;
use App\Repository\GroupSessionDispoRepository;
use App\Repository\NotificationRepository;



class SessionController extends AbstractController
{

    private $notifController;

    public function __construct(NotificationController $notifController) {

        $this->notifController = $notifController;
    }


    #[Route('/cancelSession/{sessionId}', name: 'app_cancelSession')]
    public function cancelSession(EntityManagerInterface $entityManager, int $sessionId): Response
    {

        $sessionRepo = $entityManager->getRepository(GroupSession::class);
        $session = $sessionRepo->find($sessionId);

        // Vérif Leader
        if($session->getTeam()->getLeader() == $this->getUser()) {

            // Envoi notifs annulation aux autres membres
            $this->notifController->notifCancelSession($session);

            // Vérifs cascade dispo suppr
            $sessionRepo->remove($session, true);
            
            $this->addFlash('success', 'Session annulée avec succès');
            return $this->redirectToRoute('app_groupDetails', ['groupId' => $session->getTeam()->getId()]);

        }
        else {
            $this->addFlash('error', 'Vous devez être le leader de la team pour gérer les sessions');
            return $this->redirectToRoute('app_groupDetails', ['groupId' => $session->getTeam()->getId()]);
        }

    }


    
    #[Route('/updateSessionMemberDispo/{sessionId}/{disponibility}', name: 'app_updateSessionMemberDispo')]
    public function updateSessionMemberDispo(EntityManagerInterface $entityManager, int $sessionId, string $disponibility): Response
    {

        $sessionRepo = $entityManager->getRepository(GroupSession::class);
        $sessionDispoRepo = $entityManager->getRepository(GroupSessionDispo::class);
        $session = $sessionRepo->find($sessionId);
        $group = $session->getTeam();
        $user = $this->getUser();

        // Vérif membre du groupe
        if($group->getMembers()->contains($this->getUser())) {

            // Vérif si disponibilité existe déjà -> si statut different: update
            if( !is_null($sessionDispoRepo->findOneBy(['session' => $session, 'member' => $this->getUser()])) ) {
                $sessionDispo = $sessionDispoRepo->findOneBy(['session' => $session, 'member' => $this->getUser()]);
                $sessionDispo->setDisponibility($disponibility);
            }
            else {
                $sessionDispo = new GroupSessionDispo;
                $sessionDispo->setSession($session);
                $sessionDispo->setMember($this->getUser());
                $sessionDispo->setDisponibility($disponibility);    
            }

            // Envoi notif au leader
            $this->notifController->notifNewDispo($sessionDispo);

            $entityManager->persist($sessionDispo);
            $entityManager->flush();
            
            // return $this->redirectToRoute('app_groupDetails', ['groupId' => $session->getTeam()->getId()]);
            return new JsonResponse(['success' => true]); 

        }
        else {
            $this->addFlash('error', 'Vous devez être membre du groupe pour faire cela.');
            return $this->redirectToRoute('app_groupDetails', ['groupId' => $session->getTeam()->getId()]);
        }

    }



    // Ajax récup de la disponibilité de l'user connecté quand il clique sur une session du calendrier
    #[Route('/getSessionSelfDispo/{sessionId}', name: 'app_getSessionSelfDispo')]
    public function getSessionSelfDispo(EntityManagerInterface $entityManager, int $sessionId, Request $request): Response
    {

            $sessionRepo = $entityManager->getRepository(GroupSession::class);
            $sessionDispoRepo = $entityManager->getRepository(GroupSessionDispo::class);
            $session = $sessionRepo->find($sessionId);
            $group = $session->getTeam();

            // Vérif si disponibilité existe déjà -> si statut different: update
            if( !is_null($sessionDispoRepo->findOneBy(['session' => $session, 'member' => $this->getUser()])) ) {
                $sessionDispoState = $sessionDispoRepo->findOneBy(['session' => $session, 'member' => $this->getUser()])->getDisponibility();
            }
            else {
                $sessionDispoState = null;
            }

            return new JsonResponse(['success' => true, 'selfDisponibility' => $sessionDispoState]); 

    }




    // Ajax Leader: récup des disponibilités des membres quand il clique sur une session du calendrier
    #[Route('/getSessionMembersDispo/{sessionId}', name: 'app_getSessionMembersDispo')]
    public function getSessionMembersDispo(EntityManagerInterface $entityManager, int $sessionId, Request $request): Response
    {

            $sessionRepo = $entityManager->getRepository(GroupSession::class);
            $sessionDispoRepo = $entityManager->getRepository(GroupSessionDispo::class);
            $session = $sessionRepo->find($sessionId);
            $group = $session->getTeam();
            $members = $group->getMembers();

            // Pour chaque membre: tab[id => dispo, ...]
            $membersDispoArray = [];
            foreach ($members as $member) {
                // ajout dans result si membre a une row dispo (findOneBy)
                if( !is_null($sessionDispoRepo->findOneBy(['session' => $session, 'member' => $member])) ) {
                    $membersDispoArray[] = [
                        "id" => $member->getId(),
                        "disponibility" => $sessionDispoRepo->findOneBy(['session' => $session, 'member' => $member])->getDisponibility(),
                    ];
                }
            }

            return new JsonResponse(['success' => true, 'memberDispoArray' => $membersDispoArray]); 

    }
}
