<?php

namespace App\Controller;

use App\Entity\IngredientPerRecette;
use App\Form\IngredientPerRecetteType;
use App\Repository\IngredientPerRecetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ingredient/per/recette')]
class IngredientPerRecetteController extends AbstractController
{
    #[Route('/', name: 'app_ingredient_per_recette_index', methods: ['GET'])]
    public function index(IngredientPerRecetteRepository $ingredientPerRecetteRepository): Response
    {
        return $this->render('ingredient_per_recette/index.html.twig', [
            'ingredient_per_recettes' => $ingredientPerRecetteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ingredient_per_recette_new', methods: ['GET', 'POST'])]
    public function new(Request $request, IngredientPerRecetteRepository $ingredientPerRecetteRepository): Response
    {
        $ingredientPerRecette = new IngredientPerRecette();
        $form = $this->createForm(IngredientPerRecetteType::class, $ingredientPerRecette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredientPerRecetteRepository->add($ingredientPerRecette, true);

            return $this->redirectToRoute('app_ingredient_per_recette_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ingredient_per_recette/new.html.twig', [
            'ingredient_per_recette' => $ingredientPerRecette,
            'ingredientForm' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ingredient_per_recette_show', methods: ['GET'])]
    public function show(IngredientPerRecette $ingredientPerRecette): Response
    {
        return $this->render('ingredient_per_recette/show.html.twig', [
            'ingredient_per_recette' => $ingredientPerRecette,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ingredient_per_recette_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, IngredientPerRecette $ingredientPerRecette, IngredientPerRecetteRepository $ingredientPerRecetteRepository): Response
    {
        $form = $this->createForm(IngredientPerRecetteType::class, $ingredientPerRecette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredientPerRecetteRepository->add($ingredientPerRecette, true);

            return $this->redirectToRoute('app_ingredient_per_recette_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ingredient_per_recette/edit.html.twig', [
            'ingredient_per_recette' => $ingredientPerRecette,
            'ingredientForm' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ingredient_per_recette_delete', methods: ['POST'])]
    public function delete(Request $request, IngredientPerRecette $ingredientPerRecette, IngredientPerRecetteRepository $ingredientPerRecetteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ingredientPerRecette->getId(), $request->request->get('_token'))) {
            $ingredientPerRecetteRepository->remove($ingredientPerRecette, true);
        }

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);

        // return $this->redirectToRoute('app_ingredient_per_recette_index', [], Response::HTTP_SEE_OTHER);
    }
}
