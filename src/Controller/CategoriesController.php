<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Category;
use App\Entity\Todo;
use App\Form\CategoryType;
use App\Form\TodoType;

class CategoriesController extends Controller
{
    /**
     * @Route("/app/categories", name="categories_index")
     */
    public function index(Request $request)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('categories_index');
        }

        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAllJoinedToTodos();
        
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $categories,
            $request->query->getInt('page', 1),
            10
        );
        $pagination->setCustomParameters(array(
            'align' => 'center', # center|right (for template: twitter_bootstrap_v4_pagination)
            'style' => 'bottom'
        ));

        return $this->render('categories/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/app/categories/{id}", name="categories_show", requirements={"id"="\d+"})
     */
    public function show($id, Request $request)
    {
        $todo = new Todo();

        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);

        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy([
                'id' => $id
            ]);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $todo->setCategory($category);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($todo);
            $entityManager->flush();

            return $this->redirectToRoute('categories_show', ['id' => $id]);
        }

        return $this->render('categories/show.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/app/categories/{id}/delete", name="categories_delete", requirements={"id"="\d+"})
     */
    public function delete(Category $category)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('categories_index');
    }
}
