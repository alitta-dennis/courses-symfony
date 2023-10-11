<?php

namespace App\Controller\Admin;

use App\Entity\Cart;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;



class CartCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Cart::class;
    }

    
    public function configureFields(string $pageName): iterable
    {   
        //$userAssociationField = AssociationField::new('user', 'User');
        //$userAssociationField->addCssClass('my-custom-class');
        return [
            IdField::new('id')->hideOnForm(),
            //$userAssociationField,
            AssociationField::new('email', 'User'), 
            //dump($cart->getUser()),
            //dump($user),
            AssociationField::new('course', 'Course')
            ->autocomplete()
            ->setFormTypeOptions([
                'multiple' => true,
                'by_reference' => false, 
            ]),
        ];
    }
    
}
