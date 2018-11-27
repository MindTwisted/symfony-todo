<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Todo;

class TodosController extends AbstractController
{
    /**
     * @Route("/todos/{id}/mark-as-done", name="todos_markAsDone", requirements={"id"="\d+"})
     */
    public function markAsDone(Todo $todo)
    {
        $category = $todo->getCategory();
        $todo->setIsDone(true);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($todo);
        $entityManager->flush();

        return $this->redirectToRoute('categories_show', ['id' => $category->getId()]);
    }

    /**
     * @Route("/todos/{id}/mark-as-undone", name="todos_markAsUndone", requirements={"id"="\d+"})
     */
    public function markAsUndone(Todo $todo)
    {
        $category = $todo->getCategory();
        $todo->setIsDone(false);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($todo);
        $entityManager->flush();

        return $this->redirectToRoute('categories_show', ['id' => $category->getId()]);
    }
}
