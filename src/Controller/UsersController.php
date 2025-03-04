<?php

namespace App\Controller;

use App\Entity\UserEntity;
use App\Form\UsersFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UsersController extends AbstractController
{
    #[Route('/users', name: 'users')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        //$this->denyAccessUnlessGranted('view_users');

        $users = $entityManager->getRepository(UserEntity::class)->findAll();

        return $this->render('users.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/users/delete/{id}', name: 'delete_user')]
    public function delete(int $id, EntityManagerInterface $entityManager): RedirectResponse
    {
        //$this->denyAccessUnlessGranted('view_users');

        $user = $entityManager->getRepository(UserEntity::class)->find($id);

        if ($user) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur supprimé avec succès!');
        } else {
            $this->addFlash('error', 'Utilisateur non trouvé!');
        }

        return $this->redirectToRoute('users');
    }

    #[Route('/users/edit/{id}', name: 'edit_user')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        //$this->denyAccessUnlessGranted('view_users');

        $user = $entityManager->getRepository(UserEntity::class)->find($id);

        if (!$user) {
            $this->addFlash('error', 'Utilisateur non trouvé!');
            return $this->redirectToRoute('users');
        }

        $form = $this->createForm(UsersFormType::class, $user, [
            'show_password' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur modifié avec succès!');
            return $this->redirectToRoute('users');
        }

        return $this->render('edit_user.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    #[Route('/users/add', name: 'add_user')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        //$this->denyAccessUnlessGranted('view_users');

        $user = new UserEntity();

        $form = $this->createForm(UsersFormType::class, $user, [
            'show_password' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Nouvel utilisateur ajouté avec succès!');
            return $this->redirectToRoute('users');
        }

        return $this->render('add_user.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
