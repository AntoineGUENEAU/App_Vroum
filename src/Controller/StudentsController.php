<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/student")
 * @Security("has_role('ROLE_MONITOR')")
 */
class StudentsController extends AbstractController
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
        $user->setRoles(["ROLE_MONITOR"]);
        $user->setLastname($request->get('lastname'));
        $user->setFirstname($request->get('firstname'));
        $user->setEmail($request->get('email'));
        $user->setPassword($encoder->encodePassword($user, $request->get('password')));
        $manager->persist($user);
        $manager->flush();
    }

    /**
     * @Route("/", name="student_index", methods={"GET"})
     * @param UserRepository $userRepository
     *
     * @return Response
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('students/index.html.twig', [
            'students' => $userRepository->findByRoleStudent(),
        ]);
    }

//    public function getSeriesCount()
//    {
//        $entitymanager = $this->getDoctrine()->getManager();
//        $query = $entitymanager->createQuery('aaa')->setParameter('userId', userId);
//    }

    /**
     * @Route("/new", name="student_new", methods={"GET","POST"})
     * @param Request $request
     *
     * @param UserPasswordEncoderInterface $encoder
     *
     * @return Response
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On crypte le password
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('student_index');
        }

        return $this->render('students/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="student_show", methods={"GET"})
     * @param User $user
     *
     * @return Response
     */
    public function show(User $user): Response
    {
        return $this->render('students/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="student_edit", methods={"GET","POST"})
     * @param Request $request
     * @param User $user
     *
     * @return Response
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('student_index', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('students/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="student_delete", methods={"DELETE"})
     * @param Request $request
     * @param User $user
     *
     * @return Response
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('student_index');
    }

    /**
     * @Route("/results/{id}", name="student_results", methods={"GET"})
     * @param UserRepository $userRepository
     * @param  User $user
     *
     * @return Response
     */
    public function showStudentResults(UserRepository $userRepository, User $user) : Response
    {

        $series = $userRepository->getStudentSeriesWithResults($user);

        return $this->render('students/series.html.twig', [
            'series' => $series,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/results/api/{id}", name="student_results_JSON", methods={"GET"})
     * @param UserRepository $userRepository
     * @param  User $user
     *
     * @return Response
     */
    public function sendSeriesInJson(UserRepository $userRepository, $id)
    {
        $series = $userRepository->getStudentSeriesWithResultsJson($id);
        return $this->render('students/series.html.twig', [
            'series' => $series,
        ]);
//        $data = array();
//        $data["name"]  = "olivier";
//        $data["date"]  = time();
//        $data["admin"] = true;
//        echo json_encode( $data );
    }
}
