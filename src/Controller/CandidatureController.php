<?php

namespace App\Controller;

use App\Entity\Candidature;
use App\Entity\Group;
use App\Entity\GroupQuestion;
use App\Entity\GroupAnswer;
use App\Entity\Game;
use App\Entity\User;
use App\Form\CandidatureType;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CandidatureController extends AbstractController
{

    // Affiche de la liste des candidature d'un groupe (leader)
    #[Route('/candidatureList/{groupId}', name: 'app_candidatureList')]
    public function candidatureList(EntityManagerInterface $entityManager, int $groupId, Request $request): Response
    {
        $groupRepo = $entityManager->getRepository(Group::class);
        $group = $groupRepo->find($groupId);
        $gameFrom = $group->getGame();
        $candidatures = $group->getCandidatures();

        // if is leader
        if ($this->getUser() == $group->getLeader() ) {

            return $this->render('candidature/candidatureList.html.twig', [
                'candidatures' => $candidatures,
                'group' => $group,
                'gameFrom' => $gameFrom,

            ]);
        }
        else {
            $this->addFlash('error', 'Vous devez être le leader pour accéder à cette page');
            return $this->redirectToRoute('app_groupDetails', ['groupId' => $group->getId()]);
        }

        
    }

    
    #[Route('/cancelCandidature/{candidatureId}', name: 'app_cancelCandidature')]
    public function cancelCandidature(EntityManagerInterface $entityManager, int $candidatureId, Request $request): Response
    {
        $candidatureRepo = $entityManager->getRepository(Candidature::class);
        $groupRepo = $entityManager->getRepository(Group::class);
        $candidature = $candidatureRepo->find($candidatureId);
        $group = $candidature->getGroupe();

        // Vérif si userCo est bien auteur de la candidature
        if ($candidature->getUser() == $this->getUser()) {

            $candidatureRepo->remove($candidature);
            $entityManager->flush();

            $this->addFlash('success', 'Candidature annulée');
            return $this->redirectToRoute('app_groupDetails', ['groupId' => $group->getId()]);
        }
        else {
            $this->addFlash('error', 'Vous devez être l\'auteur de la candidature pour l\'annuler');
            return $this->redirectToRoute('app_groupDetails', ['groupId' => $group->getId()]);
        }

        
    }


    // Affichage de la page de candidature (form)
    #[Route('/showCandidatureForm/{groupId}', name: 'app_showCandidatureForm')]
    public function showCandidatureForm(EntityManagerInterface $entityManager, int $groupId, Request $request): Response
    {
        $groupRepo = $entityManager->getRepository(Group::class);
        $group = $groupRepo->find($groupId);
        $gameFrom = $group->getGame();
        $groupQuestions = $group->getGroupQuestions();

        // check si pas deja membre
        if( !$group->getMembers()->contains($this->getUser()) ) {

            // check si candidature existe deja 
            $candidatureRepo = $entityManager->getRepository(Candidature::class);
            $countExist = count($candidatureRepo->findBy(["user" => $this->getUser(), "groupe" => $group]));
            if ($countExist == 0) {

                $candidature = new Candidature;
                $form = $this->createForm(CandidatureType::class, $candidature);
                $form -> handleRequest($request);

                // Vérifs/Filtres
                if($form->isSubmitted()) {
                    if($form->isValid()) {

                        $candidature = $form->getData();

                        // Vérif si Texte de candidature répondu (obligatoire) (testé)
                        if($candidature->getText() == "") {
                            $this->addFlash('error', 'Vous n\'avez pas rempli votre introduction');
                            return $this->redirectToRoute('app_showCandidatureForm', ['groupId' => $groupId]); 
                        }

                        // Associez chaque GroupAnswer à chaque GroupQuestion (candidature form)
                        $index = 0;
                        foreach ($groupQuestions as $groupQuestion) {

                            $index++;
                            $idQuestion = $groupQuestion->getId();

                            // Vérif si questions obligatoires répondues (testé)
                            if ($groupQuestion->isRequired() && $request->request->get('answer' . $index) == "" ) {
                                $this->addFlash('error', 'Vous n\'avez pas répondu à toutes les questions obligatoires');
                                return $this->redirectToRoute('app_showCandidatureForm', ['groupId' => $groupId]); 
                            }
                            
                            $answer = new GroupAnswer;
                            $answer->setCandidature($candidature);
                            $answer->setText($request->request->get('answer' . $index));
                            $answer->setGroupQuestion($groupQuestion);
                            $candidature->addGroupAnswer($answer);

                            $entityManager->persist($answer);
                        }
                        // Ou alors créer un groupReponse pour chaque input à l'ancienne + verif si exite '

                        $candidature->setUser($this->getUser());
                        $candidature->setGroupe($group);
                        $candidature->setCreationDate(new \Datetime());
                        $candidature->setStatus("pending");

                        $entityManager->persist($candidature);
                        $entityManager->flush();

                        $this->addFlash('success', 'Votre candidature a bien été envoyée');
                        return $this->redirectToRoute('app_groupDetails', ['groupId' => $groupId]); 
                    }
                }

                return $this->render('group/groupCandidatureForm.html.twig', [
                    'formCandidature' => $form->createView(),
                    'group' => $group,
                    'gameFrom' => $gameFrom,
                    'groupQuestions' => $groupQuestions,
                ]);

            }
            else {
                $this->addFlash('error', 'Vous avez déjà candidaté à cette team');
                return $this->redirectToRoute('app_groupDetails', ['groupId' => $groupId]);
            }
        }
        else {
            $this->addFlash('error', 'Vous êtes déjà membre de ce groupe ou n\'êtes pas connecté');
            return $this->redirectToRoute('app_groupDetails', ['groupId' => $groupId]);
        }
    }
}
