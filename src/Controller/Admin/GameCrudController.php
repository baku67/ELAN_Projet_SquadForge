<?php

namespace App\Controller\Admin;

use App\Entity\Game;
use App\Form\RegistrationFormType;
use App\Form\TopicType;
use App\Form\MediaType;
use App\Form\GroupType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Form\FormTypeInterface;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;


class GameCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Game::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('genre'),
            TextField::new('title')->setLabel('Titre'),
            TextField::new('editor')->setLabel('Editeur'),
            TextEditorField::new('description'),
            ColorField::new('color')->setLabel('Couleur primaire'),
            ColorField::new('font_color')->setLabel('Couleur de police des titres de section'),
            ImageField::new('logo')->setUploadDir('public\img\games\logo'),
            // ImageField::new('logo')->setUploadDir('public\img\games\logo')->setUploadedFileNamePattern('[name]')->hideOnDetail(),
            ImageField::new('banner')->setUploadDir('public\img\games\banner')->setLabel('Bannière du header'),
            ImageField::new('tiny_logo')->setUploadDir('public\img\games\tinyLogo')->setLabel('Logo miniature'),
            ImageField::new('sub_banner')->setUploadDir('public\img\games\headerBackground')->setLabel('Arrière-plan de la 1ère section'),
            ImageField::new('site_logo')->setUploadDir('public\img\games\headerSiteLogo')->setLabel('Logo du site adapté'),
            IntegerField::new('nbrPlaces')->setLabel('Nombre de place max des teams'),
            BooleanField::new('showIcon_searchPage')->setLabel('Montrer le logo dans les résultats de recherche'),
            SlugField::new('slug')->setTargetFieldName('title')->setLabel('Slug (basé sur le titre)'),
            DateTimeField::new('publish_date')->setLabel('Date de sortie du jeu'),

            // *** Collections liées au jeu (page details only):
            // CollectionField::new('gameGroups')->setEntryType(GroupType::class)->setLabel('Teams liées au jeu')->hideOnIndex(),
            CollectionField::new('topics')->setEntryType(TopicType::class)->setLabel('Topics liés au jeu')->hideOnIndex(),
            // CollectionField::new('media')->setEntryType(MediaType::class)->setLabel('Médias liés au jeu')->hideOnIndex(),
            CollectionField::new('favUsers')->setEntryIsComplex()->setLabel('Utilisateurs ayant favorisé')->hideOnIndex(),
            CollectionField::new('notations')->setLabel('Notations du jeu')->hideOnIndex(), // TODO: Chart.js
        ];
    }
    
}
