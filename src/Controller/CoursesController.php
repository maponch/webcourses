<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CourseRepository;


class CoursesController extends AbstractController
{
    #[Route('/courses', name: 'app_courses')]
    public function index(CourseRepository $courseRepository,Request $request): Response
    {
        $courses = $courseRepository->findAll();
        return $this->render('courses/index.html.twig', [
            'courses' => $courses, // Passe les cours à la vue Twig
        ]);
    }
}
