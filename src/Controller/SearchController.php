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
    #[Route('/searchLandingPage/{textInput}/{gameSelectedId}', name: 'app_searchLandingPage')]
    public function searchLandingPage(EntityManagerInterface $entityManager, string $textInput = null, int $gameSelectedId, Request $request): Response
    {

        // TODO: Validate et Sanitize $textInput et $filterArray 
        // !!!!!!!!!!!

        // if ($request->isXmlHttpRequest()) {

            // $sanitizeTextInput = $this->sanitizeTextInput($textInput);


            $gamesFilter = $request->query->get('games');
            $topicsFilter = $request->query->get('topics');
            $mediasFilter = $request->query->get('medias');
            $teamsFilter = $request->query->get('teams');


            // TODO: filters booleans

            if($gamesFilter == "true") {
                $GameRepo = $entityManager->getRepository(Game::class);
                $queryGames = $GameRepo->createQueryBuilder('g')
                ->where('g.title LIKE :searchText')
                ->setParameter('searchText', "%$textInput%");

                // Si 0: aucun jeu séléctionné
                if($gameSelectedId != 0) {
                    $queryGames->andWhere('t.game = :gameSelectedId')
                    ->setParameter('gameSelectedId', $gameSelectedId);
                }

                $queryGames->setMaxResults(5);

                $resultGames = $queryGames->getQuery()->getResult();    
            } else {
                $resultGames = null;
            }


            if($topicsFilter == "true") {
                $topicRepo = $entityManager->getRepository(Topic::class);
                $queryTopics = $topicRepo->createQueryBuilder('t')
                ->where('t.title LIKE :searchText')
                ->setParameter('searchText', "%$textInput%");

                // Si 0: aucun jeu séléctionné
                if($gameSelectedId != 0) {
                    $queryTopics->andWhere('t.game = :gameSelectedId')
                    ->setParameter('gameSelectedId', $gameSelectedId);
                }

                $queryTopics->setMaxResults(5);

                $resultTopics = $queryTopics->getQuery()->getResult();    
            } else {
                $resultTopics = null;
            }


            if($mediasFilter == "true") {
                $mediaRepo = $entityManager->getRepository(Media::class);
                $queryMedia = $mediaRepo->createQueryBuilder('m')
                ->where('m.title LIKE :searchText')
                ->setParameter('searchText', "%$textInput%");

                // Si 0: aucun jeu séléctionné
                if($gameSelectedId != 0) {
                    $queryMedia->andWhere('m.game = :gameSelectedId')
                    ->setParameter('gameSelectedId', $gameSelectedId);
                }

                $queryMedia->setMaxResults(5);

                $resultMedias = $queryMedia->getQuery()->getResult();    
            } else {
                $resultMedias = null;
            }


            if($teamsFilter == "true") {
                $groupRepo = $entityManager->getRepository(Group::class);
                $queryGroups = $groupRepo->createQueryBuilder('g')
                ->where('g.title LIKE :searchText')
                ->setParameter('searchText', "%$textInput%");

                // Si 0: aucun jeu séléctionné
                if($gameSelectedId != 0) {
                    $queryGroups->andWhere('g.game = :gameSelectedId')
                    ->setParameter('gameSelectedId', $gameSelectedId);
                }

                $queryGroups->setMaxResults(5);

                $resultTeams = $queryGroups->getQuery()->getResult();    
            } else {
                $resultTeams = null;
            }


            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            // publishDates ignored car volumineux (boucle?)
            // TODO: teams: voir pour avoir juste le game title et logo (pb de circular reference) 
            // voir pour compter les likes en back plutot que count en Front (pour Medias)
            return new JsonResponse(
                [
                    'success' => true,
                    'games' => json_decode($serializer->serialize($resultGames, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['game', 'genre', 'user', 'topics', 'media', 'favUsers', 'notations', 'gameGroups', 'publishDate']]), true),
                    'topics' => json_decode($serializer->serialize($resultTopics, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['game', 'genre', 'user', 'topic', 'topicPosts', 'publishDate']]), true),
                    'medias' => json_decode($serializer->serialize($resultMedias, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['game', 'genre', 'user', 'media', 'mediaPosts', 'publishDate', 'userUpvote']]), true),
                    'teams' => json_decode($serializer->serialize($resultTeams, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['creationDate', 'leader', 'game', 'members', 'groupQuestions', 'candidatures', 'blacklistedUsers', 'groupSessions']]), true),
                ]
            );
        // }


    }
}
