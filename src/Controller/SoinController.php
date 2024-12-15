<?php

namespace App\Controller;

use App\Entity\Soin;
use App\Form\SoinType;
use App\Repository\SoinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/soin')]
final class SoinController extends AbstractController
{
    #[Route(name: 'app_soin_index', methods: ['GET'])]
    /**
     * Affiche la liste de tous les soins
     */
    public function index(SoinRepository $soinRepository): Response
    {
        return $this->render('soin/index.html.twig', [
            'soins' => $soinRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_soin_new', methods: ['GET', 'POST'])]
    /**
     * Crée un nouveau soin
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $soin = new Soin();
        $form = $this->createForm(SoinType::class, $soin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($soin);
            $entityManager->flush();

            return $this->redirectToRoute('app_soin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('soin/new.html.twig', [
            'soin' => $soin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_soin_show', methods: ['GET'])]
    /**
     * Affiche les détails d'un soin
     */
    public function show(Soin $soin): Response
    {
        return $this->render('soin/show.html.twig', [
            'soin' => $soin,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_soin_edit', methods: ['GET', 'POST'])]
    /**
     * Affiche le formulaire de modification d'un soin
     */
    public function edit(Request $request, Soin $soin, EntityManagerInterface $entityManager): Response
    {
        // Crée un formulaire SoinType lié à l'objet $soin
        $form = $this->createForm(SoinType::class, $soin);
        // Traite la requ te HTTP en provenance du formulaire
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistre les modifications en BDD
            $entityManager->flush();

            // Redirige vers la page d'accueil des soins
            return $this->redirectToRoute('app_soin_index', [], Response::HTTP_SEE_OTHER);
        }

        // Affiche le formulaire pour modifier un soin
        return $this->render('soin/edit.html.twig', [
            'soin' => $soin,
            'form' => $form,
        ]);
    }

    /**
     * Supprime un soin
     *
     * @param Request $request
     * @param Soin $soin
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(Request $request, Soin $soin, EntityManagerInterface $entityManager): Response
    {
        // Vérifie si le token CSRF est valide pour la suppression
        if ($this->isCsrfTokenValid('delete' . $soin->getId(), $request->getPayload()->getString('_token'))) {
            // Supprime le soin de la base de données
            $entityManager->remove($soin);
            $entityManager->flush();
        }

        // Redirige vers la page d'accueil des soins
        return $this->redirectToRoute('app_soin_index', [], Response::HTTP_SEE_OTHER);
    }
}
