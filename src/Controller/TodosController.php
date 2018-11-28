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
    public function markAsDone($id)
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
            return $this->redirectToRoute('categories_index');
        }

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
    public function markAsUndone($id)
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
            return $this->redirectToRoute('categories_index');
        }
        
        $category = $todo->getCategory();
        $todo->setIsDone(false);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($todo);
        $entityManager->flush();

        return $this->redirectToRoute('categories_show', ['id' => $category->getId()]);
    }
}
