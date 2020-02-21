<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixture extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder =$encoder;
    }

    public function load(ObjectManager  $manager)
    {   for($i=0;$i<1;$i++){


        $user = new User();
        $user->setEmail('amirkassem@gmail.com');
        $user->setFirstname('amir');
        $user->setUsername('amir'.$i);
        $user->setLastname('kacem2');
        $user->setPassword($this->encoder->encodePassword($user,'pass'));
        //$this->setRoles(['ROLE_USER']);

        $manager->persist($user);
    }
        $manager->flush();
        return $user;
    }
}
