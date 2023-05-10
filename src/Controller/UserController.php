<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Game;
use App\Entity\Topic;
use Doctrine\ORM\PersistentCollection;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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

            $queryBuilder = $entityManager->createQueryBuilder();
            $queryBuilder->select('COUNT(t.id)')
                ->from(Topic::class, 't')
                ->where('t.user = :user')
                ->setParameter('user', $this->getUser());
            $userTopicsCount = $queryBuilder->getQuery()->getSingleScalarResult();

            return $this->render('user/profil.html.twig', [
                'user' => $user,
                'userRole' => $userRole,
                'userFav' => $userFav,
                'userTopics' => $userTopics,
                'userTopicsCount' => $userTopicsCount,
            ]);
        }
        else {
            return $this->render('security/login.html.twig', [
            ]);
        }
    }

    // Ajax Asynch toggleAutoplayGifs (A fixer! juste inversion bool BDD pour l'instant)
    #[Route('/toggleAutoplayGifs', name: 'app_toggleAutoplayGifs')]
    public function toggleAutoplayGifs(EntityManagerInterface $entityManager, Request $request): Response
    {

        // Récupérez les données de l'input
        // $autoPlay = $request->request->get('bool');

        $user = $this->getUser();

        // On inverse le state BDD
        if(!$user->isAutoPlayGifs()) {
            $user->setAutoPlayGifs(true);
        }
        else {
            $user->setAutoPlayGifs(false);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['success' => true]); 

    }


}
