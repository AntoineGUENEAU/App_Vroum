<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\SerieRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/student")
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
        $user->setRoles(["ROLE_STUDENT"]);
        $user->setLastname($request->get('lastname'));
        $user->setFirstname($request->get('firstname'));
        $user->setEmail($request->get('email'));
        $user->setPassword($encoder->encodePassword($user, $request->get('password')));
        $manager->persist($user);
        $manager->flush();

        return $this->render('students/thanks.html.twig', [
            'student' => $user,
        ]);
    }

    /**
     * @Route("/", name="student_index", methods={"GET"})
     * @param UserRepository $userRepository
     * @Security("has_role('ROLE_MONITOR')")
     *
     * @return Response
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('students/index.html.twig', [
            'students' => $userRepository->findByRoleStudent(),
        ]);
    }

    /**
     * @Route("/new", name="student_new", methods={"GET","POST"})
     * @param Request $request
     * @Security("has_role('ROLE_MONITOR')")
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
     * @Security("has_role('ROLE_MONITOR')")
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
     * @Security("has_role('ROLE_MONITOR')")
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
     * @Security("has_role('ROLE_MONITOR')")
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
     * @Route("/results/api/{id}", name="student_JSON_results", methods={"GET"})
     * @param UserRepository $userRepository
     * @param SerieRepository $serieRepository
     * @param $id
     * @return Response
     */
    public function sendSeriesInJson(UserRepository $userRepository, SerieRepository $serieRepository, $id)
    {
        $userResults = $userRepository->find($id)->getResults();
        $series = $serieRepository->findAll();

        $return = [];
        foreach($series as $key => $serie){
            $return[$key]['id'] = $serie->getId();
            $return[$key]['libelle'] = $serie->getLibelle();
                foreach($userResults as $userResult){
                    if ($userResult->getSerie()->getId() == $serie->getId())
                        $return[$key]['UserResult'] = $userResult->getResult();
                    else
                        $return[$key]['UserResult'] = -1;
                }
        }
        return new JsonResponse($return);
    }

}
