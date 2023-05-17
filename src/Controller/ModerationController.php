<?php

namespace App\Controller;

use App\Entity\Censure;
use App\Form\CensureType;
// use App\Entity\MediaPost;
// use App\Entity\TopicPost;
// use App\Entity\Game;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class ModerationController extends AbstractController
{
    #[Route('/moderationDashboard', name: 'app_moderationDashboard')]
    public function moderationDashboard(EntityManagerInterface $entityManager, Request $request): Response
    {

        if ( $this->getUser() && in_array('ROLE_MODO', $this->getUser()->getRoles()) ) {

            $censureRepo = $entityManager->getRepository(Censure::class);
            $censureWords = $censureRepo->findBy([], ["creation_date" => "DESC"]);
    
            // Ajouter le formType d'ajout
            $censure = new Censure;
            $form = $this->createForm(CensureType::class, $censure);
            $form -> handleRequest($request);

            if($form->isSubmitted()) {

                if($form->isValid()) {

                    $censure = $form->getData();
    
                    // Init de la publish_date du comment
                    $censure->setCreationDate(new \DateTime());
                    $censure->setUser($this->getUser());

                    // Maj BDD
                    $entityManager->persist($censure);
                    $entityManager->flush();

                    $this->addFlash('success', 'Le mot a été ajouté');
                    return $this->redirectToRoute('app_moderationDashboard');

                }

            }


            return $this->render('moderation/index.html.twig', [
                'formAddCensoredWord' => $form->createView(),
                'censureWords' => $censureWords
            ]);
    
        }
        else {

            $this->addFlash('error', 'Vous devez être connecté ou modérateur pour accéder à cette page');
            return $this->redirectToRoute('app_login');

        }

        

    }
}
