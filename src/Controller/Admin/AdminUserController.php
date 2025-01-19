<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\AdminUserType;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

class AdminUserController extends AbstractController
{
    #[Route('/admin/users', name: 'app_admin_user')]
    public function index(UserRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $users = $repository->findAll();
        $pagination = $paginator->paginate($users, $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/users/users.html.twig', [
            'users' => $pagination,
        ]);
    }
    #[Route('admin/{id}/edit', name: 'app_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdminUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Vérifiez que les rôles sont bien cohérents
                $roles = $user->getRoles();
                if (in_array('ROLE_ADMIN', $roles) && !in_array('ROLE_USER', $roles)) {
                    throw new \Exception('Un administrateur doit également avoir le rôle ROLE_USER.');
                }

                $user->setUpdatedAt(new \DateTimeImmutable());
                $entityManager->flush();

                $this->addFlash('success', 'L’utilisateur a été modifié avec succès.');

                return $this->redirectToRoute('app_admin_show', [
                    'id' => $user->getId()
                ], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur s’est produite lors de la modification de l’utilisateur : ' . $e->getMessage());
            }
        }

        return $this->render('admin/users/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    #[Route('admin/{id}', name: 'app_admin_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/users/show.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('admin/{id}', name: 'app_admin_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $token = $request->request->get('_token'); // Récupération du token CSRF
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $token)) {
            try {
                $entityManager->remove($user);
                $entityManager->flush();

                $this->addFlash('success', 'L’utilisateur a été supprimé avec succès.');
            } catch (\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException $e) {
                // Gestion de la contrainte d'intégrité (clé étrangère)
                $this->addFlash(
                    'error',
                    'Impossible de supprimer cet utilisateur car il est associé à un ou plusieurs commentaires.'
                );
            } catch (\Exception $e) {
                // Gestion générale des erreurs
                $this->addFlash('error', 'Une erreur s’est produite lors de la suppression de l’utilisateur.');
            }
        } else {
            $this->addFlash('error', 'Token CSRF invalide. Suppression non autorisée.');
        }

        return $this->redirectToRoute('app_admin_user', [], Response::HTTP_SEE_OTHER);
    }


}
