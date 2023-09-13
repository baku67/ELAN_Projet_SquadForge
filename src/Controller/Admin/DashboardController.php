<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Locale;

// use App\Entity\User;
use App\Entity\Candidature;
use App\Entity\Censure;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Group;
use App\Entity\GroupAnswer;
use App\Entity\GroupQuestion;
use App\Entity\GroupSession;
use App\Entity\GroupSessionDispo;
use App\Entity\Media;
use App\Entity\MediaPost;
use App\Entity\MediaPostLike;
use App\Entity\Notation;
use App\Entity\Notification;
use App\Entity\PostLike;
use App\Entity\Report;
use App\Entity\ReportMotif;
use App\Entity\Topic;
use App\Entity\TopicPost;
use App\Entity\User;


class DashboardController extends AbstractDashboardController
{


    // #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/{_locale}', name: 'admin')]
    public function index(): Response
    {

        if ( $this->getUser() && in_array('ROLE_ADMIN', $this->getUser()->getRoles()) ) {

            return $this->render('admin/adminDashboard.html.twig');

        }
        else {
            $this->addFlash('error', 'Vous devez être administrateur pour accéder à cette page');
            return $this->render('admin/notAdmin.html.twig');
        }
    }





    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('SquadForge - Espace Admin')
            ->setLocales([
                'en', // locale without custom options
                Locale::new('fr', 'français', 'far fa-language') // custom label and icon
            ])
            ->setFaviconPath('img/favicon_black.ico');
    }





    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-dashboard');
        yield MenuItem::linkToCrud('Jeux', 'fa-solid fa-gamepad', Game::class);
        yield MenuItem::linkToCrud('Genre', 'fa-solid fa-puzzle-piece', Genre::class);
        yield MenuItem::linkToCrud('Candidatures', 'fa-solid fa-clipboard-user', Candidature::class);
        yield MenuItem::linkToCrud('Censures', 'fa-solid fa-virus-slash', Censure::class);
    }




}
