<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    // public function configureFields(string $pageName): iterable
    // {
    //     return [
    //         AssociationField::new('cart', 'Cart')
    //         // IdField::new('id'),
    //         // TextField::new('title'),
    //         // TextEditorField::new('description'),
    //     ];
    // }
    
}
