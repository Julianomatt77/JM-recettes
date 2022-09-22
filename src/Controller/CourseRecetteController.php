<?php

namespace App\Controller;

use App\Entity\CourseRecette;
use App\Form\CourseRecetteType;
use App\Repository\CourseRecetteRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/course/recette')]
class CourseRecetteController extends AbstractController
{
    
    #[Route('/', name: 'app_course_recette_index', methods: ['GET'])]
    public function index(CourseRecetteRepository $courseRecetteRepository): Response
    {
       
        return $this->render('course_recette/index.html.twig', [
            'course_recettes' => $courseRecetteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_course_recette_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CourseRecetteRepository $courseRecetteRepository): Response
    {
        $courseRecette = new CourseRecette();
        $form = $this->createForm(CourseRecetteType::class, $courseRecette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $courseRecetteRepository->add($courseRecette, true);

            return $this->redirectToRoute('recettes', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('course_recette/new.html.twig', [
            'course_recette' => $courseRecette,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_course_recette_show', methods: ['GET'])]
    public function show(CourseRecette $courseRecette): Response
    {
        return $this->render('course_recette/show.html.twig', [
            'course_recette' => $courseRecette,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_course_recette_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CourseRecette $courseRecette, CourseRecetteRepository $courseRecetteRepository): Response
    {
        $form = $this->createForm(CourseRecetteType::class, $courseRecette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $courseRecetteRepository->add($courseRecette, true);

            return $this->redirectToRoute('app_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('course_recette/edit.html.twig', [
            'course_recette' => $courseRecette,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_course_recette_delete', methods: ['POST'])]
    public function delete(Request $request, CourseRecette $courseRecette, CourseRecetteRepository $courseRecetteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$courseRecette->getId(), $request->request->get('_token'))) {
            $courseRecetteRepository->remove($courseRecette, true);
        }

        return $this->redirectToRoute('app_course_index', [], Response::HTTP_SEE_OTHER);
    }
}
