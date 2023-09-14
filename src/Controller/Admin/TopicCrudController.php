<?php

namespace App\Controller\Admin;

use App\Entity\Topic;
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

class TopicCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Topic::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('user')->setLabel('Auteur'),
            AssociationField::new('game')->setLabel('Jeu'),
            TextField::new('title')->setLabel('Titre'),
            TextEditorField::new('firstMsg')->setLabel('Premier message'),
            TextField::new('status')->setLabel('Statut ("open" / "closed" / "closedModo")'),
            TextField::new('validated')->setLabel('Validation modo ("validated" / "refused")'),
            SlugField::new('slug')->setTargetFieldName('title')->setLabel('Slug (basé sur le titre)'),
            DateTimeField::new('publish_date')->setLabel('Date de publication'),

            CollectionField::new('topicPosts')->setLabel('Commentaires')->hideOnIndex(),
        ];
    }
    
}
