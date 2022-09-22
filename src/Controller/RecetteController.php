<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Recette;
use App\Entity\Course;
use App\Entity\IngredientPerRecette;
use App\Entity\Ingredient;
use App\Entity\Source;
use App\Entity\CourseRecette;
use App\Form\RecetteType;
use App\Form\IngredientPerRecetteType;
use App\Form\IngredientType;
use App\Form\SearchType;
use App\Form\CourseType;
use App\Repository\RecetteRepository;
use App\Repository\IngredientPerRecetteRepository;
use App\Repository\IngredientRepository;
use App\Repository\UserRepository;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Form\SourceType;
use App\Repository\SourceRepository;
use App\Form\CourseRecetteType;
use App\Repository\CourseRecetteRepository;

#[Route('/recette')]
class RecetteController extends AbstractController
{
    private $security;

    public function __construct(UserRepository $userRepository, Security $security)
    {
        $this->userRepository = $userRepository;
        $this -> security = $security;
    }


    #[Route('/', name: 'recettes', methods: ['GET', 'POST'])]
    public function index(Request $request, RecetteRepository $recetteRepository, IngredientPerRecetteRepository $ingredientPerRecetteRepository): Response
    {
        $recettes = $recetteRepository->findAllsorted();
        $connectedUser = $this->security->getUser();
        $ingredients = $ingredientPerRecetteRepository->findAll();
        $session = $request->getSession();

        if($session->has('filtres')){
            $filtres = $session->get('filtres');
            $recettes = $recetteRepository->search($filtres);

        } else {
            $filtres = [
                'search' => null,
            ];
        }

        $session = $request->getSession();

        //        Search
        $searchForm = $this->createForm(searchType::class);
        $searchForm->handleRequest($request);

         if($searchForm->isSubmitted() and $searchForm->isValid()){
            $filtres = $searchForm->getData();
            $session->set("filtres", $filtres);
            $recettes = $recetteRepository->search($filtres);
        }


        return $this->render('recette/index.html.twig', [
            'recettes' => $recettes,
            'connectedUser' => $connectedUser,
            'ingredients' => $ingredients,
            'filtres'=>$filtres,
            'searchForm' => $searchForm->createView()
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/new', name: 'recette_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        RecetteRepository $recetteRepository, 
        EntityManagerInterface $entityManager, 
        SourceRepository $sourceRepository
    ): Response
    {   
        $connectedUser = $this->security->getUser();

        $source = new Source();
        $sourceForm = $this->createForm(SourceType::class, $source);
        $sourceForm->handleRequest($request);

        if ($sourceForm->isSubmitted() && $sourceForm->isValid()) {
            $sourceRepository->add($source, true);
        }



        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recette -> setUser($connectedUser);

            $recetteRepository->add($recette, true);
            
            $lastId  = $recetteRepository->findBy(array(),array('id'=>'DESC'),1,0)[0]->getId();
            return $this->redirectToRoute('recette_edit',  ['id' => $lastId]);
        }

        return $this->renderForm('recette/new.html.twig', [
            'recette' => $recette,
            'form' => $form,
            'sourceForm' => $sourceForm,
        ]);
    }

    #[Route('/{id}', name: 'recette_show', methods: ['GET', 'POST'])]
    public function show(Recette $recette, IngredientPerRecetteRepository $ingredientPerRecetteRepository, CourseRecetteRepository $courseRecetteRepository, Request $request, CourseRepository $courseRepository): Response
    {
        $connectedUser = $this->security->getUser();
        $ingredients = $ingredientPerRecetteRepository->findAll();
        $courseRecettes = $courseRecetteRepository->findAll();

        // dd('ici');
        $course = new Course();
        $formCourse = $this->createForm(CourseType::class, $course);
        $formCourse->handleRequest($request);
        $course->setDateCourse(new \DateTime());
        if ($formCourse->isSubmitted() && $formCourse->isValid()) {
            $course->setUser($this->security->getUser()); 

            $courseRepository->add($course, true);
        }

        // Ajout de la recette à une liste de course
        $courseRecette = new CourseRecette();
        $form = $this->createForm(CourseRecetteType::class, $courseRecette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $listToUpdate = $form->getData()->getCourse()->getId();
            // dd($courseRecettes);
            
            // On vérifie si la recette éxiste déjà dans la liste de course sélectionnée
            $existing = false;
            foreach($courseRecettes as $existingCourse){ 

                if($listToUpdate == $existingCourse->getCourse()->getId() 
                && $recette->getId() == $existingCourse->getRecette()->getId()){

                    $existing = true;

                    // Si la recette est déjà dans la liste de course on update la qty  
                    $courseRecette = $existingCourse;
                    $courseRecette->setQty($courseRecette->getQty() + $form->getData()->getQty());
                    
                    $courseRecetteRepository->add($courseRecette, true);
                } 
            }

            if( $existing == false ){
                $courseRecette->setRecette($recette);
                $courseRecetteRepository->add($courseRecette, true);
            }
          return $this->redirectToRoute('recettes', [], Response::HTTP_SEE_OTHER);  
        }
        // Fin de l'ajout de la recette à une liste de course

        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
            'connectedUser' => $connectedUser,
            'ingredients' => $ingredients,
            'course_recette' => $courseRecette,
            'form' => $form->createView(),
            'formCourse' => $formCourse->createView()
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/edit', name: 'recette_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recette $recette, RecetteRepository $recetteRepository, IngredientPerRecetteRepository $ingredientPerRecetteRepository, IngredientRepository $ingredientRepository, SourceRepository $sourceRepository): Response
    {
        
        $connectedUser = $this->security->getUser();
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        
        if ($form->getData()->getUser() == $connectedUser){

            // Formulaire pour la source de la recette
            $source = new Source();
            $sourceForm = $this->createForm(SourceType::class, $source);
            $sourceForm->handleRequest($request);
            if ($sourceForm->isSubmitted() && $sourceForm->isValid()) {
                $sourceRepository->add($source, true);
            }

            // Formulaire pour l'ajout d'un ingrédient global
            $ingredientNew = new Ingredient();
            $ingredientNewform = $this->createForm(IngredientType::class, $ingredientNew);
            $ingredientNewform->handleRequest($request);
            if ($ingredientNewform->isSubmitted() && $ingredientNewform->isValid()) {
                $ingredientRepository->add($ingredientNew, true);
            }

            // Formulaire pour l'ajout d'un ingrédient pour la recette
            $ingredient = new IngredientPerRecette();
            $ingredientForm = $this->createForm(IngredientPerRecetteType::class, $ingredient);
            $ingredientForm->handleRequest($request);

            $recetteId = $form->getData()->getId();
            $recette_nb_personnes = $form->getData()->getNbPersonnes();

            if ($ingredientForm->isSubmitted() && $ingredientForm->isValid()) {
                $qty_pp = ($ingredient->getQty()) / $recette_nb_personnes;

                $recetteToEdit = $recetteRepository->find($recetteId);

                $ingredient->setRecette($recetteToEdit);
                $ingredient->setQtyPp($qty_pp);

                $ingredientPerRecetteRepository->add($ingredient, true);
            }

            // Formulaire global de la recette
            if ($form->isSubmitted() && $form->isValid()) {
                // dd($form);
                $recetteRepository->add($recette, true);

                return $this->redirectToRoute('recettes', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('recette/edit.html.twig', [
            'recette' => $recette,
            'form' => $form,
            'ingredientForm' => $ingredientForm,
            'ingredient_per_recettes' => $ingredientPerRecetteRepository->findAll(),
            'ingredientNewform' => $ingredientNewform,
            'sourceForm' => $sourceForm,
        ]);
        }
        

        return $this->redirectToRoute('recettes', [], Response::HTTP_SEE_OTHER);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/{id}', name: 'recette_delete', methods: ['POST'])]
    public function delete(Request $request, Recette $recette, RecetteRepository $recetteRepository, IngredientPerRecetteRepository $ingredientPerRecetteRepository): Response
    {
        $connectedUser = $this->security->getUser();

        // $ingredients = $ingredientPerRecetteRepository->findBy('recette' == $recette->getId());
        // dd($ingredients);

        if($recette->getUser() == $connectedUser){
            if ($this->isCsrfTokenValid('delete'.$recette->getId(), $request->request->get('_token'))) {
            $recetteRepository->remove($recette, true);
        }
        }

       

        return $this->redirectToRoute('recettes', [], Response::HTTP_SEE_OTHER);
    }
}
