<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Game;
use Doctrine\ORM\PersistentCollection;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function profil(EntityManagerInterface $entityManager): Response
    {

        if ($this->getUser()) {

            $userRepo = $entityManager->getRepository(User::class);

            $user = $userRepo->find($this->getUser()->getId());
            $userRole = $this->getUser()->getRoles();
            $userFav = $this->getUser()->getFavoris();

            // $userTopics = $user->getTopics();

            // Topics du user limit 5 du plus récent au plus ancien
            $queryBuilder = $entityManager->createQueryBuilder();
            $queryBuilder->select('t')
                ->from('App\Entity\Topic', 't')
                ->where('t.user = :user')
                ->setParameter('user', $this->getUser())
                ->orderBy('t.publish_date', 'DESC')
                ->setMaxResults(5); 
            $userTopics = $queryBuilder->getQuery()->getResult();

        $gameTopicsCount = count($userTopics);

            return $this->render('user/profil.html.twig', [
                'user' => $user,
                'userRole' => $userRole,
                'userFav' => $userFav,
                'userTopics' => $userTopics,
            ]);
        }
        else {
            return $this->render('security/login.html.twig', [
            ]);
        }
    }
}
