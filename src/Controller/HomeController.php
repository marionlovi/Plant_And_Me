<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Cette classe représente le contrôleur pour la page d'accueil
class HomeController extends AbstractController
{
    // Cette méthode gère la route '/home' et affiche la page d'accueil
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        // Rend la vue 'home/index.html.twig' avec le nom du contrôleur
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
