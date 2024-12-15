<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        // On crée l'utilisateur
        $user = new User();

        // On crée le formulaire d'inscription
        $form = $this->createForm(RegistrationFormType::class, $user);

        // On gére la requête en provenance du formulaire
        $form->handleRequest($request);

        // Si l'utilisateur est déjà connecté, on le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère le mot de passe saisi
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // On le hash pour le stocker en BDD
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // On enregistre l'utilisateur en BDD
            $entityManager->persist($user);
            $entityManager->flush();

            // On authentifie l'utilisateur
            return $security->login($user, UserAuthenticator::class, 'main');
        }

        // Affiche la page de formulaire d'inscription
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
