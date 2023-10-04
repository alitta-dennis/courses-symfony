<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

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
    //  /**
    //  * @OA\Response(
    //  *     response=200,
    //  *     description="List of categories",
    //  *     @Model(type=Category::class)
    //  * )
    //  * @OA\Tag(name="Categories")
    //  * @Security(name="Bearer")
    //  * @Route("/api/category", name="get_categories", methods={"GET"})
    //  */
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

    // #[Route('/api/category/{id}', name: 'category', methods:['GET'])]
     /**
     * @OA\Response(
     *     response=200,
     *     description="Courses that belong to selected category",
     *     @OA\JsonContent(
     *          type="array",
     *          @OA\Items(
     *               type="object",
     *                @OA\Property(property="id",type="integer"),
     *                @OA\Property(property="courseName",type="string"),
     *                @OA\Property(property="courseCode",type="string"),
     *                @OA\Property(property="price",type="number"),
     *                 
     *                ))
     * )
     * @OA\Parameter(
     *      name="Id",
     *      in="path",
     *       required=true,
     *       description="Category Id",
     *        @OA\Schema(type="integer"))
     * @OA\Tag(name="Categories")
     * @Security(name="Bearer")
     * @Route("/api/category/{id}", name="category", methods={"GET"})
     */
    public function getCoursesbyCategory($id)
    {
        $categories=$this->categoryRepository->find($id);
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
