<?php

namespace App\DataFixtures;
use App\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class CourseFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $course=new Course();
        $course->setcourseName('Artificial Intelleigence');
        $course->setCourseCode('cst123');
        $course->setStartDate('October 3,2023');
        $course->setPrice(12);
        $course->setStarRating(3.5);
        $course->setImageUrl('https://www.zdnet.com/a/img/resize/1b0f3a471607ff123236026b04b964e1853ed41b/2023/04/05/e0478a88-b3ed-4516-8459-e0b919b4b2bc/artificial-intelligence.jpg?auto=webp&fit=crop&height=900&width=1200');
        //$course->setCategory(1);
        $manager->persist($course);

        $course1=new Course();
        $course1->setcourseName('Cybersecurity');
        $course1->setCourseCode('cst456');
        $course1->setStartDate('October 23,2023');
        $course1->setPrice(1);
        $course1->setStarRating(3.5);
        $course1->setImageUrl('https://www.kaspersky.co.in/content/en-in/images/repository/isc/2017-images/What-is-Cyber-Security.jpg');
        //$course->setCategory(null);
        
        $manager->persist($course1);
        $manager->flush();
    }
}
