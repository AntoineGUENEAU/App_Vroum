<?php

namespace App\Controller;

use App\Entity\Result;
use App\Form\ResultType;
use App\Repository\ResultRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/result")
 * @Security("has_role('ROLE_MONITOR')")
 */
class ResultController extends AbstractController
{
    /**
     * @Route("/", name="result_index", methods={"GET"})
     * @param ResultRepository $resultRepository
     * @return Response
     */
    public function index(ResultRepository $resultRepository): Response
    {
        return $this->render('result/index.html.twig', [
            'results' => $resultRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="result_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $result = new Result();
        $form = $this->createForm(ResultType::class, $result);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($result);
            $entityManager->flush();

            return $this->redirectToRoute('result_index');
        }

        return $this->render('result/new.html.twig', [
            'result' => $result,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="result_show", methods={"GET"})
     * @param Result $result
     * @return Response
     */
    public function show(Result $result): Response
    {
        $student = $result->getUser()->getUsername();
        $serie = $result->getSerie()->getLibelle();
        return $this->render('result/show.html.twig', [
            'result' => $result,
            'serie' => $serie,
            'student' => $student
        ]);
    }

    /**
     * @Route("/{id}/edit", name="result_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Result $result
     * @return Response
     */
    public function edit(Request $request, Result $result): Response
    {
        $form = $this->createForm(ResultType::class, $result);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('result_index', [
                'id' => $result->getId(),
            ]);
        }

        return $this->render('result/edit.html.twig', [
            'result' => $result,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="result_delete", methods={"DELETE"})
     * @param Request $request
     * @param Result $result
     * @return Response
     */
    public function delete(Request $request, Result $result): Response
    {
        if ($this->isCsrfTokenValid('delete'.$result->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($result);
            $entityManager->flush();
        }

        return $this->redirectToRoute('result_index');
    }
}
