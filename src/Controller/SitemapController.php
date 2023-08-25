<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Media;
use App\Entity\Topic;

class SitemapController extends AbstractController
{

    public function __construct(private ManagerRegistry $doctrine) {}


    #[Route('/sitemap.xml', name: 'app_sitemap', defaults: ["_format"=>"xml"])]
    public function index(Request $request)
    {

        // Nous récupérons le nom d'hôte depuis l'URL
        $hostname = $request->getSchemeAndHttpHost();

        // On initialise un tableau pour lister les URLs
        $urls = [];

        // On ajoute les URLs "statiques"
        $urls[] = ['loc' => $this->generateUrl('app_home')];
        $urls[] = ['loc' => $this->generateUrl('app_landingPage')];
        $urls[] = ['loc' => $this->generateUrl('app_register')];
        $urls[] = ['loc' => $this->generateUrl('app_login')];
        $urls[] = ['loc' => $this->generateUrl('app_games')];

        // On ajoute les URLs dynamiques des articles dans le tableau
        // Médias:
        foreach ($this->doctrine->getRepository(Media::class)->findAll() as $article) {
            $images = [
                'loc' => '/img/uploads/'.$article->getUrl(), // URL to image
                'title' => $article->getTitle()    // Optional, text describing the image
            ];

            $urls[] = [
                'loc' => $this->generateUrl('app_mediaDetail', [
                    'id' => $article->getId(),
                ]),
                'lastmod' => $article->getPublishDate()->format('Y-m-d'),
                'image' => $images
            ];
        }

        // Topics:
        foreach ($this->doctrine->getRepository(Topic::class)->findAll() as $article) {

            $urls[] = [
                'loc' => $this->generateUrl('app_topicDetail', [
                    'id' => $article->getId(),
                ]),
                'lastmod' => $article->getPublishDate()->format('Y-m-d'),
                'title' => $article->getTitle(),
            ];
        }
    

        // Fabrication de la réponse XML
        $response = new Response(
            $this->renderView('sitemap/index.html.twig', ['urls' => $urls,
                'hostname' => $hostname]),
            200
        );

        // Ajout des entêtes
        $response->headers->set('Content-Type', 'text/xml');

        // On envoie la réponse
        return $response;

    }


}