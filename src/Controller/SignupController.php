<?php

namespace App\Controller;

use App\Entity\UserEntity;
use App\Form\SignupFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

class SignupController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/signup', name: 'signup')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new UserEntity();
        $form = $this->createForm(SignupFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $user->setPassword(
                $passwordHasher->hashPassword($user, $user->getPassword())
            );

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('signup.html.twig', [
            'signupForm' => $form->createView(),
        ]);
    }
}
