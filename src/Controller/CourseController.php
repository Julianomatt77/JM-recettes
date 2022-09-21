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
        $courses = $courseRepository->findAll();
        $ingredients = $ingredientPerRecetteRepository->findAll();

        // Lister les ingrédients
        // dd($ingredients);
        
        // $ingredientList = [];
        // foreach($recettesInList as $recette){
        //     $ingredientPerRecette = $recette->getRecette()->getIngredientPerRecettes();
        //     // dd($ingredient);
            
        //     $ing= [];
        //     foreach($ingredientPerRecette as $ingredient){
        //         // dd($ingredient);
        //         $ingArray = [];
        //         array_push($ingArray, $ingredient->getingrediient()->getName());
        //         array_push($ingArray, $ingredient->getQtyPp());
        //         array_push($ing, $ingArray);
                
        //     }
        //     array_push($ingredientList, $ing);
        // }
        // dd($ingredientList);

        // $rec = [];
        // foreach($recettesInList as $recette){
        //     // array_push($rec, $recette->getRecette());

        //     $ingredient = $recette->getRecette()->getIngredientPerRecettes();
        //     // dd($recette->getRecette());
        //     // $recettesInCourse = [$recette->getRecette()];
        //     // foreach($ingredient as $ing){
        //     //     array_push($recettesInCourse, $ing->getIngrediient());
        //     // }
        //     // array_push($rec, $recettesInCourse);
        //     array_push($rec, $ingredient);

        //     // dd($rec, $recettesInCourse);
            
        // }
        // dd($rec);

        // $recettesInCourse = [];
        // foreach($courses as $course){
        //     foreach($recettesInList as $recette ){
        //         if($recette->getCourse()->getId() == $course->getId()){                    
        //             array_push($recettesInCourse, $recette);
        //         }
        //     }
        // }        

        // dd($recettesInCourse);
        // dd($recettes);

        return $this->render('course/index.html.twig', [
            'courses' => $courses,
            'connectedUser' => $connectedUser,
            'recettes' => $recettesInList,
            // 'ingredient' => $ingredient
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
        if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->request->get('_token'))) {
            $courseRepository->remove($course, true);
        }

        return $this->redirectToRoute('app_course_index', [], Response::HTTP_SEE_OTHER);
    }
}
