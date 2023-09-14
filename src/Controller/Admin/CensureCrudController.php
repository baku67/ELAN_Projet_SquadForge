<?php

namespace App\Controller\Admin;

use App\Entity\Censure;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;


class CensureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Censure::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('user')->setLabel('Auteur'),
            TextField::new('word')->setLabel('Mot'),
            DateTimeField::new('creation_date')->setLabel('Date d\'ajout')->hideOnForm(),
        ];
    }
    
}
