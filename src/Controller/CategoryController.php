<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    private $em;
    private $categoryRepository;
    
    public function __construct(CategoryRepository $categoryRepository, EntityManagerInterface $em)
    {
        $this->em=$em;
        $this->categoryRepository=$categoryRepository;
    }
    
    #[Route('/api/category', name: 'app_category', methods:['GET'])]
    public function getCategory(): JsonResponse
    {
        $categories=$this->categoryRepository->findAll();
        $data=[];
        foreach($categories as $category)
        {
            $data[]=[
                'id'=>$category->getId(),
                'name'=>$category->getName(),
            ];
        }
        return new JsonResponse($data);
    }

    #[Route('/api/category/{id}', name: 'category', methods:['GET'])]
    public function getCoursesbyCategory($id)
    {
        $categories=$this->categoryRepository->findId($id);
        $courses=$categories->getCourses();

        $coursesArray=[];
        foreach($courses as $course)
        {
            $coursesArray[]=[
                'id'=>$course->getId(),
                'coursename'=>$course->getCourseName(),
                'coursecode'=>$course->getCourseCode(),
                'price'=>$course->getPrice(),

            ];
        }
        return new JsonResponse($coursesArray);
    }


}
