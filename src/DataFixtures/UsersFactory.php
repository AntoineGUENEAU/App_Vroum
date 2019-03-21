<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersFactory extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     */
    public function load(ObjectManager $manager)
    {
        for($i = 0 ; $i < 10 ; $i++)
        {
            $user = new User();
            $user->setRoles(["ROLE_MONITOR"]);
            $user->setLastname('monitor'.$i);
            $user->setFirstname( 'monitor'.$i);
            $user->setEmail('monitor@'.$i.'gmail.com');
            // password: admin
            $user->setPassword('$2y$13$TAZsKi7yX22MzOr9LxdaKuioM.mPQJZdM5w/shEBL2O/oxsDemoNW');
            $manager->persist($user);
        }

        for($i = 0 ; $i < 10 ; $i++)
        {
            $user = new User();
            $user->setRoles(['ROLE_STUDENT']);
            $user->setLastname('student'.$i);
            $user->setFirstname( 'student'.$i);
            $user->setEmail('student'.$i.'gmail.com');
            // password: admin
            $user->setPassword('$2y$13$TAZsKi7yX22MzOr9LxdaKuioM.mPQJZdM5w/shEBL2O/oxsDemoNW');
            $manager->persist($user);
        }
        $manager->flush();
    }
}
