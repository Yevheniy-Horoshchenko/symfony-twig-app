<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Handler\CreateUserHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;

class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'registration', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function getRegistrationTwig(
        Request $request,
        CreateUserHandler $createUserHandler,
        UserAuthenticatorInterface $userAuthenticator,
        FormLoginAuthenticator $authenticator,
    ): Response {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createUserHandler($form->getData());

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration.html.twig', ['form' => $form]);
    }
}
