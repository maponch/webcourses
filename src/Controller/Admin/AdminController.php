<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Form\CourseType;


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
    public function newCourse(): Response
    {
        $form = $this->createForm(CourseType::class);
        return $this->render('admin/newcourse.html.twig', [
            'form' => $form,
        ]);
    }
}
