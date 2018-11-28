<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class SecurityController extends AbstractController
{
    /**
     * @Route("/api/login", name="api_login", methods={"POST"})
     */
    public function login(): Response
    {
        $user = $this->getUser();
        $token = md5(random_bytes(10));
        $user->setApiToken($token);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse([
            'message' => "User {$user->getName()} was successfully logged-in.",
            'data' => [
                'token' => $token
            ]
        ]);
    }

    // /**
    //  * @Route("/register", name="register")
    //  */
    // public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    // {
    //     $user = new User();

    //     $form = $this->createForm(UserType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) 
    //     {
    //         $password = $passwordEncoder->encodePassword($user, $user->getPassword());
    //         $user->setPassword($password);

    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($user);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('login');
    //     }

    //     return $this->render(
    //         'security/register.html.twig',
    //         ['form' => $form->createView()]
    //     );
    // }
}
