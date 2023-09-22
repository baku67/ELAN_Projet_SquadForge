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
    
    
    #[Route('/searchPage', name: 'app_searchPage')]
    public function searchPage(EntityManagerInterface $entityManager, Request $request): Response
    {


        return $this->render('security/searchPage.html.twig', [

        ]);

    }



    // Recherche Asynchrone LandingPage {textInput} {gameSelectedId}
    #[Route('/searchLandingPage/{textInput}/{gameSelectedId}', name: 'app_searchLandingPage')]
    public function searchLandingPage(EntityManagerInterface $entityManager, string $textInput = null, int $gameSelectedId, Request $request): Response
    {
        $gamesFilter = $request->query->get('games');
        $topicsFilter = $request->query->get('topics');
        $mediasFilter = $request->query->get('medias');
        $teamsFilter = $request->query->get('teams');

        // Sanitize
        $textInputCleaned = strip_tags($textInput);

        if($gamesFilter == "true") {
            $gameRepo = $entityManager->getRepository(Game::class);
            $resultGames = $gameRepo->findBySearchLandingPage($textInputCleaned, $gameSelectedId);
        } else {
            $resultGames = null;
        }

        if($topicsFilter == "true") {
            $topicRepo = $entityManager->getRepository(Topic::class);
            $resultTopics = $topicRepo->findBySearchLandingPage($textInputCleaned, $gameSelectedId);
        } else {$resultTopics = null;}

        if($mediasFilter == "true") {
            $mediaRepo = $entityManager->getRepository(Media::class);
            $resultMedias = $mediaRepo->findBySearchLandingPage($textInputCleaned, $gameSelectedId);
        } else {$resultMedias = null;}

        if($teamsFilter == "true") {
            $groupRepo = $entityManager->getRepository(Group::class);
            $resultTeams = $groupRepo->findBySearchLandingPage($textInputCleaned, $gameSelectedId);
        } else {$resultTeams = null;}

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        return new JsonResponse(
            [
                'success' => true,
                'games' => json_decode($serializer->serialize($resultGames, 'json', 
                    [AbstractNormalizer::IGNORED_ATTRIBUTES => ['game', 'genre', 'user', 'topics', 'media', 'favUsers', 'notations', 'gameGroups', 'publishDate']]), true),
                'topics' => json_decode($serializer->serialize($resultTopics, 'json', 
                    [AbstractNormalizer::IGNORED_ATTRIBUTES => ['game', 'genre', 'user', 'topic', 'topicPosts', 'publishDate']]), true),
                'medias' => json_decode($serializer->serialize($resultMedias, 'json', 
                    [AbstractNormalizer::IGNORED_ATTRIBUTES => ['game', 'genre', 'user', 'media', 'mediaPosts', 'publishDate', 'userUpvote']]), true),
                'teams' => json_decode($serializer->serialize($resultTeams, 'json', 
                    [AbstractNormalizer::IGNORED_ATTRIBUTES => ['creationDate', 'leader', 'game', 'members', 'groupQuestions', 'candidatures', 'blacklistedUsers', 'groupSessions']]), true),
            ]
        );
    }
}
