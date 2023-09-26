<?php

namespace App\Controller;
use App\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CourseRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class CourseController extends AbstractController
{   
    private $courseRepository;
    private $em;

    public function __construct(CourseRepository $courseRepository, EntityManagerInterface $em){
        $this->courseRepository=$courseRepository;
        $this->em=$em;
    }
    #[Route('/api/courses', name: 'courses',methods:['GET'])]
    public function getCourses(): JsonResponse
    {
        $courses=$this->courseRepository->findAll();

        $coursesArray=[];
        foreach ($courses as $course ){
            $coursesData=$this->serializeCourses($course);
            $coursesArray[]=$coursesData;
        }
        return new JsonResponse($coursesArray);
    }

    #[Route('/api/courses/{id}', name: 'courses_get',methods:['GET'])]
    public function getCourse($id):JsonResponse
    {
        $courses=$this->courseRepository->find($id);
        $data=$this->serializeCourses($courses);
        return new JsonResponse($data);

    }

    #[Route('/api/courses',name:'create_course',methods:['POST'])]
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

        $this->em->persist($course);
        $this->em->flush();

        return new JsonResponse(['message'=>'Course Created'],JsonResponse::HTTP_CREATED);
    }

    #[Route('/api/courses/{id}',name:'edit',methods:['PUT'])]
     public function editCourse($id,Request $request):JsonResponse{

        $data = json_decode($request->getContent(), true);
        $courses=$this->courseRepository->find($id);
        
        $courses->setcourseName($data['courseName']);
        $courses->setcourseCode($data['courseCode']);
        $courses->setstartDate($data['startDate']);
        $courses->setprice($data['price']);
        $courses->setstarRating($data['starRating']);

        $this->em->flush();
        return new JsonResponse(['message'=>'Course Edited'],JsonResponse::HTTP_CREATED);
    }

    #[Route('/api/courses/img/{id}',name:'edit_img',methods:['PUT'])]
    public function editWithImg($id, Request $request):JsonResponse
    {
        $courses=$this->courseRepository->find($id);

        if(!$courses){
            return new JsonResponse(['error'=>'Product not found'],Response::HTTP_NOT_FOUND);
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
            $imageUrl='/uploads/' . $imgName;
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

    #[Route('/api/courses/{id}',name:'delete',methods:['GET','DELETE'])]
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

        ];
    }
}
