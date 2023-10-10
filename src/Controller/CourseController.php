<?php

namespace App\Controller;
use App\Entity\Course;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CourseRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

class CourseController extends AbstractController
{   
    private $courseRepository;
    private $em;

    public function __construct(CourseRepository $courseRepository, EntityManagerInterface $em){
        $this->courseRepository=$courseRepository;
        $this->em=$em;
    }
    // #[Route('/api/courses', name: 'courses',methods:['GET'])]

     /**
     * @OA\Response(
     *     response=200,
     *     description="List of courses",
     *     @Model(type=Course::class)
     * )
     * @OA\Tag(name="Courses")
     * @Security(name="Bearer")
     * @Route("/api/courses", name="get_courses", methods={"GET"})
     */
    public function getCourses(Request $request, CourseRepository $courseRepository): JsonResponse
    {   
        $page=$request->query->getInt('page',1);
        $size=$request->query->getInt('size',5);
        $courses=$courseRepository->findBy([],null,$size,($page-1)*$size);
        // $paginator=$courseRepository->paginate($page,$size);
        // $data=[];
        // foreach($paginator as $item){
        //     $data[]=[
        //         'id'=>$item->getId(),
        //         'name'=>$item->getCourseName(),
        //         'price'=>$item->getPrice(),
        //         'startDate'=>$item->getStartDate(),
        //     ];
        // }
        // $response= new JsonResponse([
        //     'data'=>$data,
        //     'pagination'=>[
        //         'page'=>$page,
        //         'size'=>$size,
        //         'total'=>$paginator->count(),
        //     ],
        // ]);
         //$courses=$courseRepository->findAll();
        $coursesArray=[];
        foreach ($courses as $course ){
            $coursesData=$this->serializeCourses($course);
            $coursesArray[]=$coursesData;
        }

        $total=count($courseRepository->findAll());
    return new JsonResponse([
        'data'=>$coursesArray,
        'total'=>$total]);
    }

    // #[Route('/api/courses/{id}', name: 'courses_get',methods:['GET'])]

     /**
     * @OA\Response(
     *     response=201,
     *     description="Details of the selected course",
     *     @Model(type=Course::class)
     * )
     * @OA\Tag(name="Courses")
     * @Security(name="Bearer")
     * @Route("/api/courses/{id}", name="get_course", methods={"GET"})
     */
    public function getCourse($id):JsonResponse
    {
        $courses=$this->courseRepository->find($id);
        $data=$this->serializeCourses($courses);
        return new JsonResponse($data);

    }

    // #[Route('/api/courses',name:'create_course',methods:['POST'])]
    /**
     * @OA\Response(
     *     response=201,
     *     description="Creates a new Course",
     *     @Model(type=Course::class)
     * )
     * @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="courseName", type="string"),
     *         @OA\Property(property="courseCode", type="string"),
     *         @OA\Property(property="startDate", type="string", format="date"),
     *         
     *         @OA\Property(property="price", type="number"),
     *         @OA\Property(property="starRating", type="number"),
     *         @OA\Property(property="categoryName", type="string"),
     *         @OA\Property(property="image", type="string", format="binary"),
     *     )
     * )
     * @OA\Tag(name="Courses")
     * @Security(name="Bearer")
     * @Route("/api/courses", name="create_course", methods={"POST"})
     */
    public function createCourse(Request $request):JsonResponse{
        
       
        // $data = json_decode($request->getContent(), true);
        
        // $course=new Course();
        
        // $course->setCoursename($data['courseName']);
        // $course->setCourseCode($data['courseCode']);
        // $course->setStartDate($data['startDate']);
        // $course->setPrice($data['price']);
        // $course->setStarRating($data['starRating']);

        // $course->setImageUrl($data['imageUrl']);

        $baseUrl="http://127.0.0.1:8000";

        $coursename=$request->request->get('courseName');
        $coursecode=$request->request->get('courseCode');
        $startDate=$request->request->get('startDate');
        $price=$request->request->get('price');
        $starRating=$request->request->get('starRating');
        $categoryName=$request->request->get('categoryName');
        $category=$this->em->getRepository(Category::class)->findOneBy(['name'=>$categoryName]);
        
        $img=$request->files->get('image');

        if(!$img){
            return new JsonResponse(['message'=>'Upload failed'],JsonResponse::HTTP_NOT_FOUND);
        }

        if($img instanceof UploadedFile )
        {
            $uploadsDirectory=$this->getParameter('kernel.project_dir').'/public/uploads';
            $imgName=md5(uniqid()).'.'.$img->guessExtension();
            $img->move($uploadsDirectory,$imgName);
            $imageUrl = '/uploads/' . $imgName;
            $imageUrl=$baseUrl.'/uploads/' . $imgName;
        }
        else{
            $imageUrl=null;
        }
        $course=new Course();
        
        $course->setCoursename($coursename);
        $course->setCourseCode($coursecode);
        $course->setStartDate($startDate);
        $course->setPrice($price);
        $course->setStarRating($starRating);
        $course->setImageUrl($imageUrl);
        $course->setCategory($category);

        

        $this->em->persist($course);
        $this->em->flush();

        return new JsonResponse(['message'=>'Course Created'],JsonResponse::HTTP_CREATED);
    }

    // #[Route('/api/courses/{id}',name:'edit',methods:['PUT'])]
    /**
     * @OA\Response(
     *     response=201,
     *     description="Edits a Course when image is not being changed",
     *     @Model(type=Course::class)
     * )
     * @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="courseName", type="string"),
     *         @OA\Property(property="courseCode", type="string"),
     *         @OA\Property(property="startDate", type="string", format="date"),
     *         
     *         @OA\Property(property="price", type="number"),
     *         @OA\Property(property="starRating", type="number"),
     *         @OA\Property(property="categoryName", type="string"),
     *         @OA\Property(property="categoryId", type="integer"),
     *         @OA\Property(property="image", type="string", format="binary"),
     *         
     *     )
     * )
     * @OA\Tag(name="Courses")
     * @Security(name="Bearer")
     * @Route("/api/courses/{id}", name="edit_course", methods={"PUT"})
     */

     public function editCourse($id,Request $request):JsonResponse{

        $user=$this->getUser();
        if(!$user){
            return $this->json(['message'=>'Invalid User','user'=>$user],401);
        }

        $data = json_decode($request->getContent(), true);
        $courses=$this->courseRepository->find($id);
        
        $courses->setcourseName($data['courseName']);
        $courses->setcourseCode($data['courseCode']);
        $courses->setstartDate($data['startDate']);
        $courses->setprice($data['price']);
        $courses->setstarRating($data['starRating']);
        $courses->setImageUrl($data['imageUrl']);
        
        
        $categoryName=$request->request->get('categoryName');
        $category=$this->em->getRepository(Category::class)->findOneBy(['name'=>$categoryName]);
        $courses->setCategory($category);

        $categoryId=$data['categoryId'];
        $category=$this->em->getRepository(Category::class)->find($categoryId);
        $courses->setCategory($category);
    
        $this->em->flush();
        return new JsonResponse(['message'=>'Course Edited','user'=>$user], JsonResponse::HTTP_CREATED);
    }

    #[Route('/api/courses/img/{id}',name:'edit_img',methods:['POST'])]

    // /**
    //  * @OA\Response(
    //  *     response=201,
    //  *     description="Edits a Course when image is being changed",
    //  *     @Model(type=Course::class)
    //  * )
    //  * @OA\RequestBody(
    //  *     required=true,
    //  *     @OA\JsonContent(
    //  *         type="object",
    //  *         @OA\Property(property="courseName", type="string"),
    //  *         @OA\Property(property="courseCode", type="string"),
    //  *         @OA\Property(property="startDate", type="string", format="date"),
    //  *         
    //  *         @OA\Property(property="price", type="number"),
    //  *         @OA\Property(property="starRating", type="number"),
    //  *         @OA\Property(property="categoryName", type="string"),
    //  *         @OA\Property(property="categoryId", type="integer"),
    //  *         @OA\Property(property="image", type="string", format="binary"),
    //  *     )
    //  * )
    //  * @OA\Tag(name="Courses")
    //  * @Security(name="Bearer")
    //  * @Route("/api/courses/img/{id}", name="edit_img", methods={"POST"})
    //  */
    public function editWithImg($id, Request $request):JsonResponse
    {
        $courses=$this->courseRepository->find($id);
        $baseUrl="http://127.0.0.1:8000";

        if(!$courses){
            return new JsonResponse(['error'=>'Course not found'],Response::HTTP_NOT_FOUND);
        }

        $coursename=$request->request->get('courseName');
        $coursecode=$request->request->get('courseCode');
        $startDate=$request->request->get('startDate');
        $price=$request->request->get('price');
        $starRating=$request->request->get('starRating');
        $imageUrl=$courses->getImageUrl();

        $img=$request->files->get('image');

        if($img instanceof UploadedFile )
        {
            $uploadsDirectory=$this->getParameter('kernel.project_dir').'/public/uploads';
            $imgName=md5(uniqid()).'.'.$img->guessExtension();
            $img->move($uploadsDirectory,$imgName);
            $imageUrl=$baseUrl.'/uploads/' . $imgName;
        }

        if($coursename!=null){
            $courses->setCourseName($coursename);
        }

        if($coursecode!=null){
            $courses->setCourseCode($coursecode);
        }

        if($startDate!=null){
            $courses->setStartDate($startDate);
        }

        if($price!=null){
            $courses->setPrice($price);
        }

        if($starRating!=null){
            $courses->setStarRating($starRating);
        }

        $courses->setImageurl($imageUrl);
        $this->em->flush();

        return new JsonResponse(['message'=>'Course Edited'],JsonResponse::HTTP_OK);
    }

     #[Route('/api/courses/{id}',name:'deletecourse',methods:['DELETE'])]
    // /**
    //  * @OA\Response(
    //  *     response=200,
    //  *     description="Delete a courses",
    //  *     @Model(type=Course::class)
    //  * )
    //  * @OA\Tag(name="Courses")
    //  * @Security(name="Bearer")
    //  * @Route("/api/courses/{id}", name="delete", methods={"DELETE"})
    //  */

    public function deleteCourse($id):Response{

        $courses=$this->courseRepository->find($id);
        $this->em->remove($courses);
        $this->em->flush();

        // return $this->redirectToRoute('courses');
        return new JsonResponse(['message'=>'Course Deleted'],JsonResponse::HTTP_CREATED);

    }
    private function serializeCourses(Course $course):array{

        return[
            'id'=>$course->getId(),
            'courseName' => $course->getCourseName(),
            'courseCode'=>$course->getCourseCode(),
            'startDate'=>$course->getStartDate(),
            'price'=>$course->getPrice(),
            'starRating'=>$course->getStarRating(),
            'imageUrl'=>$course->getImageUrl(),
            'category'=>$course->getCategory()

        ];
    }
}
