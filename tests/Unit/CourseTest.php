<?php
namespace App\Tests\Unit\CourseTest;

use App\Entity\Course;
use App\Entity\Category;
use PHPUnit\Framework\TestCase;

class CourseTest extends TestCase{

   public function testSetCourseName()
   {
        $course=new Course();
        $courseName="Compiler Design";

        $course->setCourseName($courseName);
        $this->assertEquals($courseName,$course->getCourseName());

   }

   public function testSetCourseCode()
   {
        $course=new Course();
        $courseCode="cst254";

        $course->setCourseCode($courseCode);
        $this->assertEquals($courseCode,$course->getCourseCode());

   }

   public function testSetPrice()
   {
        $course=new Course();
        $price="15";

        $course->setPrice($price);
        $this->assertEquals($price,$course->getPrice());

   }

   public function testSetStartDate()
   {
        $course=new Course();
        $startDate="November 24,2023";

        $course->setStartDate($startDate);
        $this->assertEquals($startDate,$course->getStartDate());

   }

   public function testSetStarRating()
   {
        $course=new Course();
        $starRating="3";

        $course->setStarRating($starRating);
        $this->assertEquals($starRating,$course->getStarRating());

   }

//    public function testSetCategory()
//    {
//         $course=new Course();
//         $category="Computer Science";

//         $course->setCategory($category);
//         $this->assertEquals($category,$course->getStarRating());

//    }


}
