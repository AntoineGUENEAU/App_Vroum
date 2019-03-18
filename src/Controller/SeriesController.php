<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SeriesController extends AbstractController
{
    /**
     * @Route("/series", name="series")
     */
    public function index()
    {
        return $this->render('series/index.html.twig', [
            'controller_name' => 'SeriesController',
        ]);
    }
}
