<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;



class UserFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('amirkassem@gmail.com');
        $user->setFirstname('amir');
        $user->setUsername('amir');
        $user->setLastname('kacem');
        $user->setPassword('pass');

        $manager->persist($user);

        $manager->flush();
    }
}
