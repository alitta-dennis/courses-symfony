<?php
namespace App\Tests\Unit\UserTest;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase{

    public function testSetEmail()
    {
        $user= new User();
        $email='hello@gmail.com';

        $user->setEmail($email);
        $this->assertEquals($email, $user->getEmail());
    }
}