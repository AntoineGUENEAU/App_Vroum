<?php

namespace App\Controller;

use App\Entity\Result;
use App\Entity\Serie;
use App\Form\Serie1Type;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/serie")
 */
class SerieController extends AbstractController
{
    /**
     * @Route("/", name="serie_index", methods={"GET"})
     * @param SerieRepository $serieRepository
     * @return Response
     */
    public function index(SerieRepository $serieRepository): Response
    {
        return $this->render('serie/index.html.twig', [
            'series' => $serieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="serie_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $serie = new Serie();
        $form = $this->createForm(SerieType::class, $serie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($serie);
            $entityManager->flush();

            return $this->redirectToRoute('serie_index');
        }

        return $this->render('serie/new.html.twig', [
            'serie' => $serie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="serie_show", methods={"GET"})
     * @param Serie $serie
     * @return Response
     */
    public function show(Serie $serie): Response
    {
        $questions = $serie->getQuestions();
        return $this->render('serie/show.html.twig', [
            'serie' => $serie,
            'questions' => $questions
        ]);
    }

    /**
     * @Route("/{id}/edit", name="serie_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Serie $serie
     * @return Response
     */
    public function edit(Request $request, Serie $serie): Response
    {
        $form = $this->createForm(SerieType::class, $serie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('serie_index', [
                'id' => $serie->getId(),
            ]);
        }

        return $this->render('serie/edit.html.twig', [
            'serie' => $serie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="serie_delete", methods={"DELETE"})
     * @param Request $request
     * @param Serie $serie
     * @return Response
     */
    public function delete(Request $request, Serie $serie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$serie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($serie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('serie_index');
    }

    /**
     * @Route("/api/{id}", name="serie_JSON", methods={"GET"})
     * @param Serie $serie
     * @return void
     */
    public function sendSeriesInJson(Serie $serie):JsonResponse
    {

//        Example de JSON de Francis
//        [
//            {
//                "mQuestion": "Je me promène sur une route bucolique en bord de mer et j'aborde un lacet.",
//                "mReponsesListe": [{
//                    "mReponseLabel": "J'admire le paysage",
//                        "mGoodAnswer": false
//                    }, {
//                    "mReponseLabel": "Je me concentre sur la route",
//                        "mGoodAnswer": true
//                    }
//                ],
//                "mImage": "Q1"
//            },
//            {
//                "mQuestion": "Lorsque leur avertisseurs sonores (sirène) et lumineux (gyrophares) sont en action, les véhicules du SAMU sont ils prioritaires ?",
//                "mReponsesListe": [{
//                    "mReponseLabel": "Oui",
//                        "mGoodAnswer": true
//                    }, {
//                    "mReponseLabel": "Non",
//                        "mGoodAnswer": false
//                    }
//                ],
//                "mImage": "Q2"
//            }
//        ]
        $return = [];
        foreach($serie->getQuestions() as $key => $question){
            $return[$key]['mQuestion'] = $question->getContent();
                foreach($question->getResponses() as $answer){
                    $return[$key]['mReponsesListe']['mReponseLabel'][] = $answer->getContent();
                    $return[$key]['mReponsesListe']['mGoodAnswer'][] = $answer->getGoodAnswer();
                }
            $return[$key]['mImage'] = $question->getImage();
        }
        return new JsonResponse($return);
    }

    /**
     * @Route("result/{id}", name="result_serie", methods={"POST"})
     *
     * @param Request $request
     * @param UserRepository $userRepository
     *
     */
    public function resultSerieId(Request $request, UserRepository $userRepository)
    {
        var_dump('salut');
        exit;
        $result = new Result();

        $email = $request->get('email');
        $user = $userRepository->findOneBy(array('email' => $email));
        $result->setResult($request->get('result'));
        $result->setSerie($request->get('serieId'));
        $result->setUser($user);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($result);
        $entityManager->flush();
    }
}
