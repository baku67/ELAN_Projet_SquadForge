<?php

namespace App\Controller;

use Doctrine\Common\Collections\Collection;
use App\Entity\Game;
use App\Entity\Topic;
use App\Entity\Media;
use App\Entity\Group;
use App\Entity\Genre;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\PersistentCollection;


class SearchController extends AbstractController
{
    #[Route('/searchLandingPage/{textInput}', name: 'app_searchLandingPage')]
    public function searchLandingPage(EntityManagerInterface $entityManager, string $textInput = null, Request $request): Response
    {

        // TODO: Validate et Sanitize $textInput et $filterArray 
        // !!!!!!!!!!!

        // if ($request->isXmlHttpRequest()) {

            // $sanitizeTextInput = $this->sanitizeTextInput($textInput);


            // Gerer filtre jeu / noJeu

            $gamesFilter = $request->query->get('games');
            $topicsFilter = $request->query->get('topics');
            $mediasFilter = $request->query->get('medias');
            $teamsFilter = $request->query->get('teams');

            // TODO: boolean 
            if($gamesFilter == "true") {
                $GameRepo = $entityManager->getRepository(Game::class);
                $queryGames = $GameRepo->createQueryBuilder('t')
                ->where('t.title LIKE :searchText')
                ->setParameter('searchText', "%$textInput%")
                ->setMaxResults(3)
                ->getQuery();
                $resultGames = $queryGames->getResult();    
            } else {
                $resultGames = null;
            }


            if($topicsFilter == "true") {
                $topicRepo = $entityManager->getRepository(Topic::class);
                $queryTopics = $topicRepo->createQueryBuilder('t')
                ->where('t.title LIKE :searchText')
                ->setParameter('searchText', "%$textInput%")
                ->setMaxResults(3)
                ->getQuery();
                $resultTopics = $queryTopics->getResult();    
            } else {
                $resultTopics = null;
            }


            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            // publishDate ignored car volumineux (boucle?)
            // voir pour compter les likes en back plutot que count en Front
            return new JsonResponse(
                [
                    'success' => true,
                    'games' => json_decode($serializer->serialize($resultGames, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['game', 'genre', 'user', 'topics', 'media', 'favUsers', 'notations', 'gameGroups', 'publishDate']]), true),
                    'topics' => json_decode($serializer->serialize($resultTopics, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['game', 'genre', 'user', 'topic', 'topicPosts', 'publishDate']]), true),
                ]
            );
        // }


    }
}
