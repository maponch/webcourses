<?php

namespace App\Controller\Admin;

use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Form\CourseType;
use Cocur\Slugify\Slugify;


class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/admin/newcourse', name: 'app_admin_newcourse')]
    public function newCourse(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            dd($user);
        }
        $course = new Course();
        $slugify = new Slugify();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid()){
            $course->setUser($this->getUser())
                ->setCreatedAt(new \DateTimeImmutable())
                ->setSlug($slugify->slugify($course->getName()));
            $manager->persist($course);
            $manager->flush();
            return $this->redirectToRoute('app_admin');
        }
        return $this->render('admin/newcourse.html.twig', [
            'form' => $form,
        ]);

    }
}
