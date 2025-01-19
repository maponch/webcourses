<?php

namespace App\Controller\Admin;

use App\Entity\Course;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\CourseType;
use Cocur\Slugify\Slugify;


class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'user' => $user
        ]);
    }
    #[Route('/admin/courses', name: 'app_admin_courses')]
    public function course(CourseRepository $courseRepository,Request $request): Response
    {
        $courses = $courseRepository->findAll();
        return $this->render('admin/courses/courses.html.twig', [
            'courses' => $courses, // Passe les cours à la vue Twig
        ]);
    }
    #[Route('/admin/courses/newcourse', name: 'app_admin_newcourse')]
    public function newCourse(Request $request, EntityManagerInterface $manager, CourseRepository $courseRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour créer un cours.');
            return $this->redirectToRoute('app_login');
        }

        $course = new Course();
        $slugify = new Slugify();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Génération du slug
            $slug = $slugify->slugify($course->getName());

            // Vérification de l'existence préalable
            $existingCourse = $courseRepository->findOneBy(['slug' => $slug]);

            if ($existingCourse) {
                $this->addFlash('error', 'Un cours avec ce nom existe déjà. Veuillez choisir un autre nom.');
                return $this->redirectToRoute('app_admin_newcourse');
            }

            // Ajout des informations et persistance
            $course->addUser($user)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setSlug($slug);

            $manager->persist($course);
            $manager->flush();

            $this->addFlash('success', 'Le cours a été créé avec succès.');
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/courses/newcourse.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/admin/delcourse/{id}', name: 'app_admin_delcourse')]
    public function delPost(EntityManagerInterface $manager, Course $post): Response
    {
        $manager->remove($post);
        $manager->flush();
        $this->addFlash(
            'success',
            'L\'article a bien été supprimé'
        );
        return $this->redirectToRoute('app_admin_courses');
    }
    #[Route('/admin/editcours/{id}', name: 'app_admin_editcours')]
    public function editPost(Course $course, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->addFlash(
                'success',
                'Le cours a bien été modifié'
            );
            $manager->flush();
            return $this->redirectToRoute('app_admin_courses');
        }
        return $this->render('admin/courses/edit.html.twig', [
            'form' => $form,
        ]);
    }







}
