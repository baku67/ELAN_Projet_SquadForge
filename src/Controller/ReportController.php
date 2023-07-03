<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Report;
use App\Entity\Media;
use App\Entity\Topic;
use App\Entity\MediaPost;
use App\Entity\TopicPost;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ReportController extends AbstractController
{
    #[Route('/report', name: 'app_report')]
    public function index(): Response
    {
        return $this->render('report/index.html.twig', [
            'controller_name' => 'ReportController',
        ]);
    }

    
    #[Route('/app_reportObject/{objectType}/{objectId}/{reporterId}/{reportMotifId}', name: 'app_reportObject')]
    public function reportObject(EntityManagerInterface $entityManager, string $objectType, int $objectId, int $reporterId, string $reportMotifId, Request $request): Response
    {

        $mediaRepo = $entityManager->getRepository(Media::class);


        switch ($objectType) {
            case 'media':
                $object = $mediaRepo->find($objectId);
                break;
            
            default:
                // Ne correspond à aucun object -> error
                break;
        }


        if($this->getUser()) {

            if(!is_null($object)) { 



            // Si pas deja de report pour ce user et cet objet (Type+Id) {user->getReports contains ce new report selon les 2 id?}
            
            // Si motifId not null et != 0 (value par defaut)

            // Si media existe
            
            
            // New Report



                // redirect plutot
                return $this->render('report/index.html.twig', [
                    'controller_name' => 'ReportController',
                ]);

            }

        }
        else {
            // Flash
        }

        
    }



}
