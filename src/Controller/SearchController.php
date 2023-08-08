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


            $games = $request->query->get('games');
            $topics = $request->query->get('topics');
            $medias = $request->query->get('medias');
            $teams = $request->query->get('teams');

            // Handle AJAX request, return JSON response
            $topicRepo = $entityManager->getRepository(Topic::class);

            $query = $topicRepo->createQueryBuilder('t')
            ->where('t.title LIKE :searchText')
            ->setParameter('searchText', "%$textInput%")
            ->setMaxResults(5)
            ->getQuery();
            $resultTopics = $query->getResult();

            if (empty($resultTopics)) {
                $resultTopics = null;
            }

            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            
            return new JsonResponse(
                [
                    'success' => true,
                    'topics' => json_decode($serializer->serialize($resultTopics, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['game','genre','user','topic']]), true),
                    // 'topics' => $topicRepo->find(13),
                ]
            );
        // }


    }
}
