<?php

namespace App\Controller;

use App\Form\Type\LoginForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{

    public function __construct(private readonly AuthenticationUtils $authenticationUtils)
    {
    }

    #[Route(path: '/admin/login', name: 'app_login')]
    public function loginAction(): Response
    {
        $form = $this->createForm(LoginForm::class, [
            'email' => $this->authenticationUtils->getLastUsername()
        ]);

        return $this->render('login/login.html.twig', [
            'last_username' => $this->authenticationUtils->getLastUsername(),
            'form' => $form->createView(),
            'error' => $this->authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    #[Route(path: '/admin/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
