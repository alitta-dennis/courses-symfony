<?php

namespace App\DataFixtures;
use App\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {   
        $category1=new Category();
        
        $category1->setName('Computer Science');
        $manager->persist($category1);
        $manager->flush();

        $category2=new Category();
        $category2->setName('Civil Engineering');
        $manager->persist($category2);
        $manager->flush();

        $category3=new Category();
        $category3->setName('Mechanical Engineering');
        $manager->persist($category3);
        $manager->flush();

        $category4=new Category();
        $category4->setName('Circuit Branches');
        $manager->persist($category4);
        $manager->flush();

        $this->addReference('category1',$category1);
        $this->addReference('category2',$category2);

        $this->addReference('category3',$category3);
        $this->addReference('category4',$category4);
    }
}