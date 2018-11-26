<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('pages/index.html.twig');
    }

     /**
     * @Route("/app", name="app_index")
     */
    public function app()
    {
        return $this->render('pages/app.html.twig');
    }
}
