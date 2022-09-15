<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Entity\IngredientPerRecette;
use App\Form\RecetteType;
use App\Form\IngredientPerRecetteType;
use App\Repository\RecetteRepository;
use App\Repository\IngredientPerRecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recette')]
class RecetteController extends AbstractController
{
    #[Route('/', name: 'recettes', methods: ['GET'])]
    public function index(RecetteRepository $recetteRepository): Response
    {
        return $this->render('recette/index.html.twig', [
            'recettes' => $recetteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'recette_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RecetteRepository $recetteRepository, EntityManagerInterface $entityManager): Response
    {
        
        // $ingredient = new IngredientPerRecette();

        // $ingredientForm = $this->createForm(IngredientPerRecetteType::class, $ingredient);
        // $ingredientForm->handleRequest($request);

        // if ($ingredientForm->isSubmitted() && $ingredientForm->isValid()) {
        //     $recetteRepository->add($ingredient, true);

        //     // return $this->redirectToRoute('recettes', [], Response::HTTP_SEE_OTHER);
        // }

        $recette = new Recette();

        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recetteRepository->add($recette, true);
            

            // $lastId = $recetteRepository->findLastInserted();
            $lastId  = $recetteRepository->findBy(array(),array('id'=>'DESC'),1,0)[0]->getId();
            // dd($results);
            return $this->redirectToRoute('recette_edit',  ['id' => $lastId]);
        }

        return $this->renderForm('recette/new.html.twig', [
            'recette' => $recette,
            'form' => $form,
            // 'ingredientForm' => $ingredientForm
        ]);
    }

    #[Route('/{id}', name: 'recette_show', methods: ['GET'])]
    public function show(Recette $recette): Response
    {
        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
        ]);
    }

    #[Route('/{id}/edit', name: 'recette_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recette $recette, RecetteRepository $recetteRepository, IngredientPerRecetteRepository $ingredientPerRecetteRepository): Response
    {
        

        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        $ingredient = new IngredientPerRecette();
        $ingredientForm = $this->createForm(IngredientPerRecetteType::class, $ingredient);
        $ingredientForm->handleRequest($request);

        $recetteId = $form->getData()->getId();
        $recette_nb_personnes = $form->getData()->getNbPersonnes();

        // dd($ingredientPerRecetteRepository->findAll());

        if ($ingredientForm->isSubmitted() && $ingredientForm->isValid()) {
            $qty_pp = ($ingredient->getQty()) / $recette_nb_personnes;

            $recetteToEdit = $recetteRepository->find($recetteId);

            $ingredient->setRecette($recetteToEdit);
            $ingredient->setQtyPp($qty_pp);

            $ingredientPerRecetteRepository->add($ingredient, true);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $recetteRepository->add($recette, true);

            return $this->redirectToRoute('recettes', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recette/edit.html.twig', [
            'recette' => $recette,
            'form' => $form,
            'ingredientForm' => $ingredientForm,
            'ingredient_per_recettes' => $ingredientPerRecetteRepository->findAll()
        ]);
    }

    #[Route('/{id}', name: 'recette_delete', methods: ['POST'])]
    public function delete(Request $request, Recette $recette, RecetteRepository $recetteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recette->getId(), $request->request->get('_token'))) {
            $recetteRepository->remove($recette, true);
        }

        return $this->redirectToRoute('recettes', [], Response::HTTP_SEE_OTHER);
    }
}
