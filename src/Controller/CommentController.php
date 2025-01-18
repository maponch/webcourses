<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/comment')]
final class CommentController extends AbstractController
{
    #[Route(name: 'app_comment_index', methods: ['GET'])]
    public function index(CommentRepository $commentRepository): Response
    {
        return $this->render('comment/index.html.twig', [
            'comments' => $commentRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_comment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,int $id, CourseRepository $courseRepository, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $course = $courseRepository->find($id);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if(!$this->getUser()){
            $this->redirectToRoute('app_register');
        }
        if (!$course) {
            throw $this->createNotFoundException('Le cours demandé n\'existe pas.');
        }
        $existingComment = $commentRepository->findOneBy([
            'user' => $this->getUser(),
            'course' => $course,
        ]);
        if ($existingComment) {
            $this->addFlash('warning', 'Vous avez déjà commenté ce cours.');
            return $this->redirectToRoute('app_course', ['id' => $course->getId()]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser())
                    ->setDate(new \DateTimeImmutable())
                    ->setCourse($course);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_course', ['id' => $course->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comment_show', methods: ['GET'])]
    public function show(Comment $comment): Response
    {
        return $this->render('comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_comment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comment_delete', methods: ['POST'])]
    public function delete(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
    }
}
