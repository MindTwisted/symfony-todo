<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Todo;
use App\Entity\Category;
use App\Service\Helper;

class TodosController extends AbstractController
{
    /**
     * @Route("/api/todos", name="api_todos_index", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $user = $this->getUser();
        $todos = $this->getDoctrine()
            ->getRepository(Todo::class)
            ->findByToArray([
                'userId' => $user->getId()
            ]);

        return new JsonResponse([
            'data' => $todos
        ]);
    }

    /**
     * @Route("/api/todos/{id}", name="api_todos_show", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function show($id): JsonResponse
    {
        $user = $this->getUser();
        $todo = $this->getDoctrine()
            ->getRepository(Todo::class)
            ->findOneByToArray([
                'id' => $id,
                'userId' => $user->getId()
            ]);

        if (!count($todo))
        {
            return new JsonResponse([
                'message' => "Resource not found."
            ], 404);
        }

        return new JsonResponse([
            'data' => $todo
        ]);
    }

    /**
     * @Route("/api/todos", name="api_todos_store", methods={"POST"})
     */
    public function store(
        ValidatorInterface $validator, 
        Request $request, 
        Helper $helper
    ): JsonResponse
    {
        $user = $this->getUser();

        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy([
                'id' => $request->get('category_id'),
                'user' => $user->getId()
            ]);

        if (!$category)
        {
            return new JsonResponse([
                'message' => "Category not found."
            ], 404);
        }

        $todo = new Todo();
        $todo->setTitle((string) $request->get('title'));
        $todo->setBody((string) $request->get('body'));
        $errors = $validator->validate($todo);

        if (count($errors) > 0) 
        {
            return new JsonResponse([
                'message' => 'Provided data doesn\'t valid.',
                'data' => $helper->violationsToArray($errors)
            ], 422);
        }

        $todo->setUser($user);
        $todo->setCategory($category);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($todo);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Todo was successfully stored.',
            'data' => [
                'id' => $todo->getId()
            ]
        ]);
    }

    /**
     * @Route("/api/todos/{id}", name="api_todos_update", requirements={"id"="\d+"}, methods={"PUT"})
     */
    public function update(
        $id, 
        Helper $helper,
        Request $request, 
        ValidatorInterface $validator
    ): JsonResponse
    {
        $user = $this->getUser();
        $todo = $this->getDoctrine()
            ->getRepository(Todo::class)
            ->findOneBy([
                'id' => $id,
                'user' => $user->getId()
            ]);

        if (!$todo)
        {
            return new JsonResponse([
                'message' => "Resource not found."
            ], 404);
        }

        $todo->setTitle((string) $request->get('title'));
        $todo->setBody((string) $request->get('body'));
        $errors = $validator->validate($todo);

        if (count($errors) > 0) 
        {
            return new JsonResponse([
                'message' => 'Provided data doesn\'t valid.',
                'data' => $helper->violationsToArray($errors)
            ], 422);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($todo);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Todo was successfully updated.'
        ]);
    }

    /**
     * @Route("/api/todos/{id}/mark-as-done", name="api_todos_markAsDone", requirements={"id"="\d+"}, methods={"PUT"})
     */
    public function markAsDone($id): JsonResponse
    {
        $user = $this->getUser();
        $todo = $this->getDoctrine()
            ->getRepository(Todo::class)
            ->findOneBy([
                'id' => $id,
                'user' => $user->getId()
            ]);

        if (!$todo)
        {
            return new JsonResponse([
                'message' => "Resource not found."
            ], 404);
        }

        $todo->setIsDone(true);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($todo);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Todo was successfully marked as done.'
        ]);
    }

    /**
     * @Route("/api/todos/{id}/mark-as-undone", name="api_todos_markAsUndone", requirements={"id"="\d+"}, methods={"PUT"})
     */
    public function markAsUndone($id): JsonResponse
    {
        $user = $this->getUser();
        $todo = $this->getDoctrine()
            ->getRepository(Todo::class)
            ->findOneBy([
                'id' => $id,
                'user' => $user->getId()
            ]);

        if (!$todo)
        {
            return new JsonResponse([
                'message' => "Resource not found."
            ], 404);
        }

        $todo->setIsDone(false);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($todo);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Todo was successfully marked as undone.'
        ]);
    }

    /**
     * @Route("/api/todos/{id}", name="api_todos_delete", requirements={"id"="\d+"}, methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $user = $this->getUser();
        $todo = $this->getDoctrine()
            ->getRepository(Todo::class)
            ->findOneBy([
                'id' => $id,
                'user' => $user->getId()
            ]);

        if (!$todo)
        {
            return new JsonResponse([
                'message' => "Resource not found."
            ], 404);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($todo);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Todo was successfully deleted.'
        ]);
    }
}
