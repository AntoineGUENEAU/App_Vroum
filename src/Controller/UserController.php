<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $user->setRole(USER::STUDENT_TYPE);
        $user->setLastname($request->get('lastname'));
        $user->setFirstname( $request->get('firstname'));
        $user->setEmail($request->get('email'));
        $user->setPassword($encoder->encodePassword($user, $request->get('password')));
        $manager->persist($user);
        $manager->flush();

    }
}
