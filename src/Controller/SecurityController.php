<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // La route pour la page de connexion
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, on le redirige vers la page d'accueil
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        // On récupère le message d'erreur si l'utilisateur a oublié son mot de passe
        $error = $authenticationUtils->getLastAuthenticationError();
        // On récupère le nom d'utilisateur si l'utilisateur a oublié son nom d'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    // La route pour se déconnecter
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // On redirige vers la page d'accueil aprés déconnexion
        $this->redirectToRoute('app_home_page');
        // On lance une exception car la méthode ne fait rien sinon
        throw new \LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }
}
