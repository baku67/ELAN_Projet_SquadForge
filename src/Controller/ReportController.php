<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Report;
use App\Entity\Media;
use App\Entity\Topic;
use App\Entity\MediaPost;
use App\Entity\TopicPost;
use App\Entity\ReportMotif;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ReportController extends AbstractController
{

    
    #[Route('/app_reportObject/{objectType}/{objectId}/{reporterId}/{reportMotifId}', name: 'app_reportObject')]
    public function reportObject(EntityManagerInterface $entityManager, string $objectType, int $objectId, int $reporterId, string $reportMotifId, Request $request): Response
    {

        $mediaRepo = $entityManager->getRepository(Media::class);
        $reportRepo = $entityManager->getRepository(Report::class);
        $reportMotifRepo = $entityManager->getRepository(ReportMotif::class);


        switch ($objectType) {
            case 'media':
                $object = $mediaRepo->find($objectId);
                break;
            
            default:
                // Ne correspond à aucun object -> error
                break;
        }


        if($this->getUser()) {

            // Si l'objet reporté existe
            if(!is_null($object)) { 

            
            // Si motifId not null et != 0 (value par defaut)

                // Check si Objet déjà signalé
                $checkExisting = $reportRepo->findOneBy(["objectType" => $objectType, "objectId" => $objectId, "user_reporter" => $this->getUser()]);

                if(is_null($checkExisting)) {
                
                    // New Report

                    $reportMotif = $reportMotifRepo->find($reportMotifId);

                    $report = new Report;
                    $report->setUserReporter($this->getUser());
                    $report->setObjectId($objectId);
                    $report->setObjectType($objectType);
                    $report->setCreationDate(new \DateTime());
                    $report->setReportMotif($reportMotif);

                    $entityManager->persist($report);
                    $entityManager->flush();


                    // redirect dynamique plutot
                    switch ($objectType) {
                        case 'media':
                            
                            $this->addFlash('success', 'Votre signalement a été envoyé à la modération');
                            return $this->redirectToRoute('app_mediaDetail', ['id' => $object->getId()]);

                            break;
        
                    }
                }
                else {
                    switch ($objectType) {
                        case 'media':
                            
                            $this->addFlash('error', 'Vous avez déjà signaler ce contenu');
                            return $this->redirectToRoute('app_mediaDetail', ['id' => $object->getId()]);

                            break;
        
                    }
                }
            }
        }
        else {
            switch ($objectType) {
                case 'media':
                    
                    $this->addFlash('error', 'Vous devez vous connecter pour signaler un contenu');
                    return $this->redirectToRoute('app_mediaDetail', ['id' => $object->getId()]);

                    break;

            }
        }

    }



}
