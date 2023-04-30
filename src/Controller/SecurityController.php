<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {

            if ($this->getUser()->isVerified()) {
                $_SESSION["error"] = "Your account is not verified.";
                return $this->render('security/frerfzfeqzeq.html.twig');
                // throw new CustomUserMessageAuthenticationException('Your account is not verified.');
            }
            else {
                // return $this->redirectToRoute('app_home');
                return $this->render('security/home.html.twig');
            }
        }


        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/', name: 'app_landingPage')]
    #[Route(path: '/home', name: 'app_home')]
    public function homepage()
    {
        return $this->render('security/home.html.twig', [
            
        ]);
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
