<?php

namespace App\Controller\Admin;

use App\Entity\GroupSession;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

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


class GroupSessionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GroupSession::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('team')->setLabel('Team'),
            TextField::new('title')->setLabel('Intitulé de la session'),
            DateTimeField::new('dateStart')->setLabel('Début de la session'),
            DateTimeField::new('dateStart')->setLabel('Fin de la session'),
            BooleanField::new('comfirmNeeded')->setLabel('Confirmation des membres requise'),

            CollectionField::new('groupSessionDispos')->hideOnIndex()->setLabel('Disponibilité des membres')->setEntryIsComplex(),
        ];
    }
    
}
