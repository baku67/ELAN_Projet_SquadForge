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

    
    #[Route('/app_reportObject/{objectType}/{objectId}/{reporterId}', name: 'app_reportObject')]
    public function reportObject(EntityManagerInterface $entityManager, string $objectType, int $objectId, int $reporterId, Request $request): Response
    {

        if($this->getUser()) {


            // Si pas deja de report pour ce user et cet objet (Type+Id) {user->getReports contains ce new report selon les 2 id?}
            // New Report




            return $this->render('report/index.html.twig', [
                'controller_name' => 'ReportController',
            ]);

        }
        else {
            // Flash
        }

        
    }



}
