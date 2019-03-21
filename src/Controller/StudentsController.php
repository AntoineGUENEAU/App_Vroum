<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/student")
 * @Security("has_role('ROLE_MONITOR')")
 */
class StudentsController extends AbstractController
{
    /**
     * @Route("/", name="student_index", methods={"GET"})
     * @param UserRepository $userRepository
     *
     * @return Response
     */
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        /* nombre de série effectuée par un user, par */
        $seriesCount = [];
        foreach ($users as $user) {
            $seriesCount[$user->getId()] = $userRepository->getSeriesCount($user->getId());
        }

        return $this->render('students/index.html.twig', [
            'users' => $users,
            'seriesCount' => $seriesCount
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

//        $qb = $this->querybuilder->createQueryBuilder();
//        $qb
//            ->select('a', 'u')
//            ->from('Credit\Entity\UserCreditHistory', 'a')
//            ->leftJoin('a.user', 'u')
//            ->where('u = :user')
//            ->setParameter('user', $user)
//            ->getQuery()
//            ->getResult();

        return $this->render('students/series.html.twig', [
            'series' => $series,
            'user' => $user,
        ]);
    }
}
