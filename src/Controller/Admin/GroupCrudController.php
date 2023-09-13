<?php

namespace App\Controller\Admin;

use App\Entity\Group;
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



class GroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Group::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('leader'),
            AssociationField::new('game')->setLabel('Jeu'),
            TextField::new('title')->setLabel('Nom'),
            TextEditorField::new('description'),
            IntegerField::new('nbrPlaces')->setLabel('Nombre de places'),
            BooleanField::new('restriction_18')->setLabel('Majorité requise'),
            BooleanField::new('restriction_mic')->setLabel('Micro requis'),
            TextField::new('status')->setLabel('Visibilité ("hidden"/"public")'),
            ImageField::new('imgUrl')->setLabel('Image de la Team'),
            TextEditorField::new('candidature_txt')->setLabel('Texte de recrutement'),


            DateTimeField::new('creation_date')->hideOnForm(),

        ];
    }
}