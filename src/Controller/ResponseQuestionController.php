<?php

namespace App\Controller;

use App\Entity\ResponseQuestion;
use App\Form\ResponseQuestionType;
use App\Repository\ResponseQuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/response/question")
 */
class ResponseQuestionController extends AbstractController
{
    /**
     * @Route("/", name="response_question_index", methods={"GET"})
     */
    public function index(ResponseQuestionRepository $responseQuestionRepository): Response
    {
        return $this->render('response_question/index.html.twig', [
            'response_questions' => $responseQuestionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="response_question_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $responseQuestion = new ResponseQuestion();
        $form = $this->createForm(ResponseQuestionType::class, $responseQuestion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($responseQuestion);
            $entityManager->flush();

            return $this->redirectToRoute('response_question_index');
        }

        return $this->render('response_question/new.html.twig', [
            'response_question' => $responseQuestion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="response_question_show", methods={"GET"})
     */
    public function show(ResponseQuestion $responseQuestion): Response
    {
        return $this->render('response_question/show.html.twig', [
            'response_question' => $responseQuestion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="response_question_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ResponseQuestion $responseQuestion): Response
    {
        $form = $this->createForm(ResponseQuestionType::class, $responseQuestion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('response_question_index', [
                'id' => $responseQuestion->getId(),
            ]);
        }

        return $this->render('response_question/edit.html.twig', [
            'response_question' => $responseQuestion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="response_question_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ResponseQuestion $responseQuestion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$responseQuestion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($responseQuestion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('response_question_index');
    }
}
