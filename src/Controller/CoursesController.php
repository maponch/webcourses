<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CourseRepository;
use function PHPUnit\Framework\throwException;


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
    #[Route('/course/{id}', name: 'app_course')]
    public function course(CourseRepository $repository,CommentRepository $commentRepository, int $id): Response
    {
        dd($commentRepository->averageRate());
        $course = $repository->find($id);
        if (!$course) {
            throw $this->createNotFoundException('Ce cours n\'existe pas.');
        }
        $comments = $commentRepository->findBy([
            'course' => $course
        ]);
        return $this->render('courses/detail.html.twig', [
            'course' => $course,
            'comments' => $comments
        ]);
    }

}
