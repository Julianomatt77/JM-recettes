<?php

namespace App\Controller;

use App\Entity\Course;
use Symfony\Component\Security\Core\Security;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use App\Repository\CourseRecetteRepository;
use App\Repository\IngredientPerRecetteRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/course')]
class CourseController extends AbstractController
{
    private $security;

    public function __construct(UserRepository $userRepository, Security $security)
    {
        $this->userRepository = $userRepository;
        $this -> security = $security;
    }

    #[Route('/', name: 'app_course_index', methods: ['GET'])]
    public function index(CourseRepository $courseRepository, Request $request, CourseRecetteRepository $courseRecetteRepository, IngredientPerRecetteRepository $ingredientPerRecetteRepository): Response
    {
        $connectedUser = $this->security->getUser();
        $session = $request->getSession();

        $recettesInList = $courseRecetteRepository->findAll();
        $courses = $courseRepository->findAllSorted();
        $ingredients = $ingredientPerRecetteRepository->findAll();

        // Lister les ingrédients
        
        $ingredientList = [];
        
        // Boucle pour chaque liste de course
        foreach($courses as $liste){    
            $list = [];       
            $listeId = $liste->getId();
            $listeName = $liste->getName();

            $ingredientsListPerCourse = [];

            // Récupération des recettes pour chaque liste de course
            foreach($recettesInList as $recette){

                // Si la recette appartient à la liste de course
                if($recette->getCourse()->getId() == $liste->getId()){

                    $qtyPerRecette = $recette->getQty();

                    // Récupération de tous les ingrédients de la recette
                    $ingredientPerRecette = $recette->getRecette()->getIngredientPerRecettes();                    
                    
                    foreach($ingredientPerRecette as $ingredient){
                        $ingredientId = $ingredient->getingrediient()->getId();
                        $ingredientName = $ingredient->getingrediient()->getName();
                        $unite = $ingredient->getUnite();
                        $qtyTotal = 0;
                        $qtyTotal = $ingredient->getQtyPp() * $qtyPerRecette;
                        $existing = false;

                        // Si l'ingrédient existe déjà -> update de la qty
                        if ($ingredientsListPerCourse != null){
                            foreach($ingredientsListPerCourse as $key => $existingIngredient){
                                if($ingredientName == $existingIngredient['name']){
                                    $newQty = $existingIngredient['qty'] + $qtyTotal;
                                    $ingredientsListPerCourse[$key]['qty'] = $newQty;
                                    $existing = true;
                                }
                            }
                        }
                        
                        $QtyPerIngredient = [];
                        
                        if($existing == false){
                            $QtyPerIngredient = [
                                'id' => $ingredientId,
                                'name' => $ingredientName,
                                'qty' => $qtyTotal,
                                'unite' => $unite,
                            ];
                            array_push($ingredientsListPerCourse, $QtyPerIngredient);
                        }
                    }

                }
            }
            $list = [
                'id' => $listeId,
                'name' => $listeName,
                'ingredients' => $ingredientsListPerCourse
            ];
            array_push($ingredientList, $list);
        }
        // dd($ingredientList);

        return $this->render('course/index.html.twig', [
            'courses' => $courses,
            'connectedUser' => $connectedUser,
            'recettes' => $recettesInList,
            'ingredientList' => $ingredientList
        ]);
    }

    #[Route('/new', name: 'app_course_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CourseRepository $courseRepository): Response
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        $course->setDateCourse(new \DateTime());

        if ($form->isSubmitted() && $form->isValid()) {

            $course->setUser($this->security->getUser()); 

            $courseRepository->add($course, true);

            return $this->redirectToRoute('app_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('course/new.html.twig', [
            'course' => $course,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_course_show', methods: ['GET'])]
    public function show(Course $course): Response
    {
        return $this->render('course/show.html.twig', [
            'course' => $course,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_course_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Course $course, CourseRepository $courseRepository): Response
    {
        $date = $course->getDateCourse();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $course->setDateCourse($date);
            $courseRepository->add($course, true);

            return $this->redirectToRoute('app_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('course/edit.html.twig', [
            'course' => $course,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_course_delete', methods: ['POST'])]
    public function delete(Request $request, Course $course, CourseRepository $courseRepository): Response
    {
        $connectedUser = $this->security->getUser();

        if($course->getUser() == $connectedUser){
            if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->request->get('_token'))) {
                $courseRepository->remove($course, true);
            }
        }

        return $this->redirectToRoute('app_course_index', [], Response::HTTP_SEE_OTHER);
    }
}
