<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

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


    
    #[Route('/sessionMemberDispo/{sessionId}/{disponibility}', name: 'app_sessionMemberDispo')]
    public function sessionMemberDispo(EntityManagerInterface $entityManager, int $sessionId, string $disponibility): Response
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
            
            $this->addFlash('success', 'Disponibilité envoyée');
            return $this->redirectToRoute('app_groupDetails', ['groupId' => $session->getTeam()->getId()]);

        }
        else {
            $this->addFlash('error', 'Vous devez être membre du groupe pour faire cela.');
            return $this->redirectToRoute('app_groupDetails', ['groupId' => $session->getTeam()->getId()]);
        }

    }
}
