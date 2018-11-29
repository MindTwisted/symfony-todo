<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Service\Helper;

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

    /**
     * @Route("/api_register", name="api_register", methods={"POST"})
     */
    public function register(
        Helper $helper,
        Request $request, 
        ValidatorInterface $validator, 
        UserPasswordEncoderInterface $passwordEncoder
    ): Response
    {
        $user = new User();
        $user->setName((string) $request->get('name'));
        $user->setEmail((string) $request->get('email'));
        $user->setPassword((string) $request->get('password'));
        $errors = $validator->validate($user);

        if (count($errors) > 0) 
        {
            return new JsonResponse([
                'message' => 'Provided data doesn\'t valid.',
                'data' => $helper->violationsToArray($errors)
            ], 422);
        }
        
        $password = $passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'User was successfully registered.'
        ]);
    }
}
