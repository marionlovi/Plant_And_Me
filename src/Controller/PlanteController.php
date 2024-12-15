<?php

namespace App\Controller;

use App\Entity\Plante;
use App\Form\PlanteType;
use App\Repository\PlanteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Contrôleur pour gérer les opérations CRUD sur l'entité Plante
#[Route('/plante')]
final class PlanteController extends AbstractController
{
    // Affiche la liste de toutes les plantes
    #[Route(name: 'app_plante_index', methods: ['GET'])]
    public function index(PlanteRepository $planteRepository): Response
    {
        return $this->render('plante/index.html.twig', [
            'plantes' => $planteRepository->findAll(),
        ]);
    }

    // Crée une nouvelle plante
    #[Route('/new', name: 'app_plante_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Crée une nouvelle instance de Plante
        $plante = new Plante();

        // Crée un formulaire basé sur le formulaire PlanteType
        $form = $this->createForm(PlanteType::class, $plante);

        // Traite la requête HTTP en provenance du formulaire
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistre la plante en BDD
            $entityManager->persist($plante);
            $entityManager->flush();

            // Redirige vers la page d'accueil
            return $this->redirectToRoute('app_plante_index', [], Response::HTTP_SEE_OTHER);
        }

        // Affiche le formulaire pour créer une plante
        return $this->render('plante/new.html.twig', [
            'plante' => $plante,
            'form' => $form,
        ]);
    }

    // Affiche les détails d'une plante spécifique
    #[Route('/{id}', name: 'app_plante_show', methods: ['GET'])]
    public function show(Plante $plante): Response
    {
        return $this->render('plante/show.html.twig', [
            'plante' => $plante,
        ]);
    }

    // Modifie une plante existante
    #[Route('/{id}/edit', name: 'app_plante_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Plante $plante, EntityManagerInterface $entityManager): Response
    {
        // Crée un formulaire basé sur le formulaire PlanteType
        $form = $this->createForm(PlanteType::class, $plante);

        // Traite la requête HTTP en provenance du formulaire
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistre les modifications en BDD
            $entityManager->flush();

            // Redirige vers la page d'accueil
            return $this->redirectToRoute('app_plante_index', [], Response::HTTP_SEE_OTHER);
        }

        // Affiche le formulaire pour modifier une plante
        return $this->render('plante/edit.html.twig', [
            'plante' => $plante,
            'form' => $form,
        ]);
    }


    // Supprime une plante
    #[Route('/{id}', name: 'app_plante_delete', methods: ['POST'])]
    // Supprime une plante de la base de données
    public function delete(Request $request, Plante $plante, EntityManagerInterface $entityManager): Response
    {
        // Vérifie si le token CSRF est valide pour la suppression
        if ($this->isCsrfTokenValid('delete' . $plante->getId(), $request->getPayload()->getString('_token'))) {
            // Supprime la plante de la base de données
            $entityManager->remove($plante);
            $entityManager->flush();
        }

        // Redirige vers la page de liste des plantes
        return $this->redirectToRoute('app_plante_index', [], Response::HTTP_SEE_OTHER);
    }
}
