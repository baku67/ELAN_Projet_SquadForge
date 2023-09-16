<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;



class MediaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Media::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title')->setLabel('Titre'),
            AssociationField::new('game')->setLabel('Jeu'),
            AssociationField::new('user')->setLabel('Auteur'),
            ImageField::new('url')->setLabel('Image uploadé')->setUploadDir('public\img\uploads'),
            TextField::new('status')->setLabel('Statut ("open" / "closed" / "closedModo")'),
            TextField::new('validated')->setLabel('Validation modo ("validated" / "refused")'),
            SlugField::new('slug')->setTargetFieldName('title')->setLabel('Slug (basé sur le titre)'),
            DateTimeField::new('publish_date')->setLabel('Date de publication'),

            CollectionField::new('mediaPosts')->setLabel('Commentaires')->hideOnIndex(),
            CollectionField::new('userUpvote')->setLabel('Upvotes')->hideOnIndex(),
        ];
    }
    
}
