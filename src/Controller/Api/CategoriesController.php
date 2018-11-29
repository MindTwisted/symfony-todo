<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Category;
use App\Entity\Todo;
use App\Service\Helper;

class CategoriesController extends AbstractController
{
    /**
     * @Route("/api/categories", name="api_categories_index", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $user = $this->getUser();
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findByToArray([
                'userId' => $user->getId()
            ]);

        return new JsonResponse([
            'data' => $categories
        ]);
    }

    /**
     * @Route("/api/categories/{id}", name="api_categories_show", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function show($id): JsonResponse
    {
        $user = $this->getUser();
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneByToArray([
                'id' => $id,
                'userId' => $user->getId()
            ]);

        if (!count($category))
        {
            return new JsonResponse([
                'message' => "Resource not found."
            ], 404);
        }

        return new JsonResponse([
            'data' => $category
        ]);
    }

    /**
     * @Route("/api/categories/{id}/todos", name="api_categories_showTodos", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function showTodos($id): JsonResponse
    {
        $user = $this->getUser();
        $todos = $this->getDoctrine()
            ->getRepository(Todo::class)
            ->findByToArray([
                'categoryId' => $id,
                'userId' => $user->getId()
            ]);

        return new JsonResponse([
            'data' => $todos
        ]);
    }

    /**
     * @Route("/api/categories", name="api_categories_store", methods={"POST"})
     */
    public function store(
        ValidatorInterface $validator, 
        Request $request, 
        Helper $helper
    ): JsonResponse
    {
        $user = $this->getUser();
        $category = new Category();
        $category->setName((string) $request->get('name'));
        $errors = $validator->validate($category);

        if (count($errors) > 0) 
        {
            return new JsonResponse([
                'message' => 'Provided data doesn\'t valid.',
                'data' => $helper->violationsToArray($errors)
            ], 422);
        }

        $category->setUser($user);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($category);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Category was successfully stored.',
            'data' => [
                'id' => $category->getId()
            ]
        ]);
    }

    /**
     * @Route("/api/categories/{id}", name="api_categories_update", requirements={"id"="\d+"}, methods={"PUT"})
     */
    public function update(
        $id, 
        Helper $helper,
        Request $request, 
        ValidatorInterface $validator
    ): JsonResponse
    {
        $user = $this->getUser();
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy([
                'id' => $id,
                'user' => $user->getId()
            ]);

        if (!$category)
        {
            return new JsonResponse([
                'message' => "Resource not found."
            ], 404);
        }

        $category->setName((string) $request->get('name'));
        $errors = $validator->validate($category);

        if (count($errors) > 0) 
        {
            return new JsonResponse([
                'message' => 'Provided data doesn\'t valid.',
                'data' => $helper->violationsToArray($errors)
            ], 422);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($category);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Category was successfully updated.'
        ]);
    }

    /**
     * @Route("/api/categories/{id}", name="api_categories_delete", requirements={"id"="\d+"}, methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $user = $this->getUser();
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy([
                'id' => $id,
                'user' => $user->getId()
            ]);

        if (!$category)
        {
            return new JsonResponse([
                'message' => "Resource not found."
            ], 404);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Category was successfully deleted.'
        ]);
    }
}
