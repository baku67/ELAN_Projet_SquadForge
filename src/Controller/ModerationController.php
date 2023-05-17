<?php

namespace App\Controller;

use App\Entity\Censure;
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
    public function moderationDashboard(EntityManagerInterface $entityManager): Response
    {

        if ( $this->getUser() && in_array('ROLE_MODO', $this->getUser()->getRoles()) ) {
            $censureRepo = $entityManager->getRepository(Censure::class);
            $censureWords = $censureRepo->findAll();
    
            return $this->render('moderation/index.html.twig', [
                'censureWords' => $censureWords
            ]);
    
        }

        return $this->render('moderation/index.html.twig', [
            
        ]);

    }
}
