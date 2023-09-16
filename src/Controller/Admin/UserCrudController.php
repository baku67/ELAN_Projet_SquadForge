<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;


class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('pseudo'),
            EmailField::new('email'),
            ArrayField::new('roles'),
            TextField::new('status')->setlabel('Statut ("muted"/"banned"/null)'),
            DateTimeField::new('endDateStatus'),
            IntegerField::new('nbrCensures'),

            CollectionField::new('favoris')->setLabel('Favoris')->hideOnIndex(),
            CollectionField::new('topics')->setLabel('Topics publiés')->hideOnIndex(),
            CollectionField::new('media')->setLabel('Médias publiés')->hideOnIndex(),
            CollectionField::new('topicPosts')->setLabel('Commentaires publiés (Topics)')->hideOnIndex(),
            CollectionField::new('mediaPosts')->setLabel('Commentaires publiés (Médias)')->hideOnIndex(),
            CollectionField::new('leadedGroups')->setLabel('Teams leadées')->hideOnIndex(),
            CollectionField::new('groupes')->setLabel('Membre')->hideOnIndex(),
            CollectionField::new('candidatures')->setLabel('Candidatures en cours')->hideOnIndex(),
            CollectionField::new('groupsWhereBlackisted')->setLabel('Blacklisté')->hideOnIndex(),
            CollectionField::new('reports')->setLabel('Signalements envoyés')->hideOnIndex(),
        ];
    }
    
}
