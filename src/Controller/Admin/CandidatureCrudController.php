<?php

namespace App\Controller\Admin;

use App\Entity\Candidature;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;


class CandidatureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Candidature::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('groupe')->setLabel('Team'),
            AssociationField::new('user')->setLabel('Utilisateur'),
            TextEditorField::new('text')->setLabel('Motivation'),
            TextField::new('status')->setLabel('Statut ("pending" ou vide)'),
            DateTimeField::new('creation_date')->setLabel('Date de candidature')->hideOnForm(),
        
            CollectionField::new('groupAnswers')->setLabel('Réponses aux questions')->hideOnIndex()->setEntryIsComplex(),

        ];
    }
    
}
