<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoriesController extends AbstractController
{
    /**
     * @Route("/api/categories", name="api_categories_index")
     */
    public function index()
    {
        return new JsonResponse([
            'data' => 123
        ]);
    }
}
