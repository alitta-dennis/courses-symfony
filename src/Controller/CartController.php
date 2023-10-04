<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Course;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

use App\Repository\CartRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


   #[Route('/api/cart', name: 'app_cart')]
    class CartController extends AbstractController
{
    private $cartRepository;
    private $em;

    public function __construct(CartRepository $cartRepository, EntityManagerInterface $em){
        $this->cartRepository=$cartRepository;
        $this->em=$em;
    }

    #[Route('/add/{id}', name: 'add',methods:['POST'])]

//      /**
//      * @OA\Response(
//      *     response=201,
//      *     description="Adding to cart",
//      *     @Model(type=Cart::class)
//      * ),
//      *  @OA\Response(
//  *         response=401,
//  *         description="Invalid User",
//  *         @OA\JsonContent(
//  *             type="object",
//  *             @OA\Property(property="error", type="string", example="Invalid user")
//  *         )
//  *     ),
//  *     @OA\Response(
//  *         response=404,
//  *         description="Cart Empty",
//  *         @OA\JsonContent(
//  *             type="object",
//  *             @OA\Property(property="error", type="string", example="Cart empty")
//  *         )
//  *     )
//      * @OA\Tag(name="Cart")
//      * @Security(name="Bearer")
//      * @Route("cart/add/{id}", name="add", methods={"POST"})
//      */ 
    public function add(int $id):JsonResponse
    {
        $user=$this->getUser();
        if(!$user)
        {
            return $this->json(['message'=>'Invalid User','user'=>$user],401);
        }

        $course=$this->em->getRepository(Course::class)->find($id);

        $cart= $user->getCart();

        if(!$cart)
        {
            $cart=new Cart();
            $cart->setUserId($user);
        }
        $cart->addCourse($course);
        $this->em->persist($cart);
        $this->em->flush();

        return $this->json(['message'=>'Course added']);

    }

    #[Route('/view', name: 'view',methods:['GET'])]
    public function view():JsonResponse
    {
        $user=$this->getUser();
        if(!$user)
        {
            return $this->json(['message'=>'Invalid User'],401);
        }

        $cart=$user->getCart();
        if(!$cart)
        {
            return $this->json(['message'=>'User does not have a cart'],404);
        }

        $courses=$cart->getCourse();

        $coursesArray[]=[];
        foreach ($courses as $course)
        {
            $coursesArray[]=
                
                [
                    'id'=>$course->getId(),
                    'courseName'=>$course->getcourseName(),
                    'coursecode'=>$course->getcourseCode(),
                    'price'=>$course->getprice(),
                    'startDate'=>$course->getstartDate(),
                    'starRating'=>$course->getstarRating(),
                    'imageUrl'=>$course->getimageUrl()

                ];
        }
        
        return $this->json([
            'email'=>$user->getEmail(),
             'courses'=>$coursesArray,
        ]);

    }

    #[Route('/delete/{id}', name: 'delete',methods:['POST'])]

//     /**
//      * @OA\Response(
//      *     response=200,
//      *     description="Course Deleted",
//      *     @Model(type=Cart::class)
//      * ),
//      *  @OA\Response(
//  *         response=401,
//  *         description="Invalid User",
//  *         @OA\JsonContent(
//  *             type="object",
//  *             @OA\Property(property="error", type="string", example="Invalid user")
//  *         )
//  *     ),
//  *     @OA\Response(
//  *         response=404,
//  *         description="Cart Empty",
//  *         @OA\JsonContent(
//  *             type="object",
//  *             @OA\Property(property="error", type="string", example="Cart empty")
//  *         )
//  *     )
//      * @OA\Tag(name="Cart")
//      * @Security(name="Bearer")
//      * @Route("api/cart/delete/{id}", name="delete", methods={"POST"})
//      */ 
    public function delete(int $id):JsonResponse
    {
        $user=$this->getUser();

        if(!$user)
        {
            return $this->json(['message'=>'Invalid User'],401);
        }

        $course=$this->em->getRepository(Course::class)->find($id);
        $cart=$user->getCart();
        if(!$cart)
        {
            return $this->json(['message'=>'User does not have a cart'],401);
        }

        $cart->removeCourse($course);
        
        $this->em->flush();
        return $this->json(['message'=>'Course removed from cart']);
    }



    
}

